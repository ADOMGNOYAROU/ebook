<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription as CashierSubscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        $tenant = session('tenant');
        $subscription = $tenant->subscription;
        $currentPlan = $tenant->currentPlan();
        $plans = Plan::active()->get();

        return view('subscriptions.index', compact('tenant', 'subscription', 'currentPlan', 'plans'));
    }

    public function checkout(Plan $plan)
    {
        $tenant = session('tenant');
        
        // Vérifier si le tenant a déjà un abonnement actif
        if ($tenant->isSubscribed()) {
            return redirect()->route('subscriptions.index')
                ->with('info', 'Vous avez déjà un abonnement actif.');
        }

        return view('subscriptions.checkout', compact('tenant', 'plan'));
    }

    public function processCheckout(Request $request, Plan $plan)
    {
        $tenant = session('tenant');
        $user = Auth::user();

        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        try {
            // Créer ou récupérer le client Stripe
            $stripeCustomer = $user->createOrGetStripeCustomer();

            // Créer l'abonnement Stripe
            $stripeSubscription = $user->newSubscription(
                'default', // nom de l'abonnement
                $plan->stripe_price_id
            )->create($request->payment_method_id, [
                'email' => $user->email,
                'metadata' => [
                    'tenant_id' => $tenant->id,
                    'tenant_name' => $tenant->name,
                ],
            ]);

            // Créer l'abonnement en base de données
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'stripe_subscription_id' => $stripeSubscription->stripe_id,
                'status' => $stripeSubscription->stripe_status,
                'trial_ends_at' => $stripeSubscription->trial_ends_at,
                'current_period_start' => $stripeSubscription->current_period_start,
                'current_period_end' => $stripeSubscription->current_period_end,
                'amount' => $plan->price,
                'currency' => 'eur',
                'metadata' => [
                    'payment_method_id' => $request->payment_method_id,
                ],
            ]);

            return redirect()->route('subscriptions.success')
                ->with('success', 'Abonnement créé avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l\'abonnement : ' . $e->getMessage());
        }
    }

    public function success()
    {
        $tenant = session('tenant');
        $subscription = $tenant->subscription;

        return view('subscriptions.success', compact('tenant', 'subscription'));
    }

    public function cancel()
    {
        $tenant = session('tenant');
        $subscription = $tenant->subscription;

        if (!$subscription) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Aucun abonnement à annuler.');
        }

        return view('subscriptions.cancel', compact('tenant', 'subscription'));
    }

    public function processCancel(Request $request)
    {
        $tenant = session('tenant');
        $subscription = $tenant->subscription;

        if (!$subscription) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Aucun abonnement à annuler.');
        }

        try {
            $user = Auth::user();
            $stripeSubscription = $user->subscription('default');

            if ($request->input('immediately') === 'true') {
                // Annulation immédiate
                $stripeSubscription->cancelNow();
                $subscription->update([
                    'status' => 'canceled',
                    'ends_at' => now(),
                ]);
            } else {
                // Annulation à la fin de la période
                $stripeSubscription->cancel();
                $subscription->update([
                    'metadata' => array_merge($subscription->metadata ?? [], [
                        'cancel_at_period_end' => true,
                    ]),
                ]);
            }

            return redirect()->route('subscriptions.index')
                ->with('success', 'Abonnement annulé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'annulation : ' . $e->getMessage());
        }
    }

    public function resume()
    {
        $tenant = session('tenant');
        $subscription = $tenant->subscription;

        if (!$subscription || !$subscription->willCancelAtPeriodEnd()) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Cet abonnement ne peut pas être réactivé.');
        }

        try {
            $user = Auth::user();
            $stripeSubscription = $user->subscription('default');
            $stripeSubscription->resume();

            $subscription->update([
                'metadata' => array_merge($subscription->metadata ?? [], [
                    'cancel_at_period_end' => false,
                ]),
            ]);

            return redirect()->route('subscriptions.index')
                ->with('success', 'Abonnement réactivé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la réactivation : ' . $e->getMessage());
        }
    }

    public function swap(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $tenant = session('tenant');
        $subscription = $tenant->subscription;
        $newPlan = Plan::findOrFail($request->plan_id);

        if (!$subscription) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Aucun abonnement trouvé.');
        }

        try {
            $user = Auth::user();
            $stripeSubscription = $user->subscription('default');

            // Changer de plan sur Stripe
            $stripeSubscription->swap($newPlan->stripe_price_id);

            // Mettre à jour l'abonnement en base
            $subscription->update([
                'plan_id' => $newPlan->id,
                'amount' => $newPlan->price,
            ]);

            return redirect()->route('subscriptions.index')
                ->with('success', 'Plan changé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du changement de plan : ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('cashier.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid webhook signature'], 400);
        }

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentSucceeded($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)
            ->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($invoice->period_end),
            ]);
        }
    }

    private function handlePaymentFailed($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)
            ->first();

        if ($subscription) {
            $subscription->update(['status' => 'past_due']);
        }
    }

    private function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)
            ->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'canceled',
                'ends_at' => now(),
            ]);
        }
    }

    private function handleSubscriptionUpdated($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)
            ->first();

        if ($subscription) {
            $subscription->update([
                'status' => $stripeSubscription->status,
                'current_period_start' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start),
                'current_period_end' => \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end),
            ]);
        }
    }
}
