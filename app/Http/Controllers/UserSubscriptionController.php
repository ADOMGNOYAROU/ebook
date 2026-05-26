<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PayGateGlobalService;

class UserSubscriptionController extends Controller
{
    protected PayGateGlobalService $paygate;

    public function __construct(PayGateGlobalService $paygate)
    {
        $this->paygate = $paygate;
    }

    /**
     * Affiche la page de souscription
     */
    public function index()
    {
        $user = Auth::user();
        return view('subscription.index', compact('user'));
    }

    /**
     * Process le paiement pour l'abonnement premium
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:8|max:15',
            'network' => 'required|in:FLOOZ,MOOV,TOGOCEL,TMONEY',
            'plan' => 'required|in:monthly,yearly',
        ]);

        $user = Auth::user();

        if ($user->isPremium()) {
            return back()->with('info', 'Vous avez déjà un abonnement premium actif.');
        }

        try {
            $planPrices = [
                'monthly' => 9.99,
                'yearly' => 99.99,
            ];

            $identifier = 'premium_' . $user->id . '_' . time();

            $paymentUrl = $this->paygate->createPaymentPage([
                'amount' => $planPrices[$request->plan],
                'description' => 'Abonnement Premium ' . ($request->plan === 'monthly' ? 'Mensuel' : 'Annuel'),
                'identifier' => $identifier,
                'return_url' => route('user.subscription.success') . '?identifier=' . $identifier . '&plan=' . $request->plan,
                'phone' => $request->phone,
                'network' => $request->network,
            ]);

            if (!$paymentUrl) {
                throw new \Exception('Impossible de créer la page de paiement');
            }

            session(['subscription_identifier' => $identifier, 'subscription_plan' => $request->plan]);

            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Succès de la souscription
     */
    public function success(Request $request)
    {
        $identifier = $request->get('identifier');
        $plan = $request->get('plan');

        if (!$identifier || !$plan) {
            return redirect()->route('user.subscription.index')->with('error', 'Paiement invalide.');
        }

        try {
            $status = $this->paygate->checkPaymentStatusByIdentifier($identifier);

            if (!$status || !isset($status['status']) || $status['status'] != '0') {
                return redirect()->route('user.subscription.index')
                    ->with('error', 'Le paiement n\'a pas été validé.');
            }

            $user = Auth::user();
            $months = $plan === 'yearly' ? 12 : 1;

            $user->upgradeToPremium($months);

            return redirect()->route('dashboard')->with('success', 'Félicitations ! Vous êtes maintenant Premium !');

        } catch (\Exception $e) {
            return redirect()->route('user.subscription.index')->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Annulation de la souscription
     */
    public function cancel(Request $request)
    {
        return view('subscription.cancel');
    }
}
