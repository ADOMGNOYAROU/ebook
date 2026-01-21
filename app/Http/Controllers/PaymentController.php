<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    /**
     * Affiche le formulaire de paiement
     */
    public function checkout($ebookId)
    {
        $ebook = Ebook::findOrFail($ebookId);
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà acheté cet ebook
        if ($user->hasPurchased($ebook->id)) {
            return redirect()->route('public.ebooks.show', $ebook->slug)
                ->with('info', 'Vous avez déjà acheté cet ebook.');
        }

        return view('payment.checkout', compact('ebook'));
    }

    /**
     * Traite le paiement
     */
    public function processPayment(Request $request, $ebookId)
    {
        $user = Auth::user();
        $ebook = Ebook::findOrFail($ebookId);

        try {
            // Créer une session de paiement Stripe
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $ebook->title,
                            'description' => substr($ebook->description, 0, 100) . '...',
                        ],
                        'unit_amount' => $ebook->price * 100, // Montant en centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}&ebook_id=' . $ebook->id,
                'cancel_url' => route('payment.cancel'),
                'customer_email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'ebook_id' => $ebook->id,
                ],
            ]);

            return redirect()->away($session->url);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors du traitement de votre paiement: ' . $e->getMessage());
        }
    }

    /**
     * Gère le retour réussi de Stripe
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $ebookId = $request->get('ebook_id');
        
        if (!$sessionId || !$ebookId) {
            return redirect()->route('public.ebooks.index')
                ->with('error', 'Session de paiement invalide.');
        }

        try {
            $session = Session::retrieve($sessionId);
            
            // Vérifier si le paiement a réussi
            if ($session->payment_status === 'paid') {
                $user = Auth::user();
                $ebook = Ebook::findOrFail($ebookId);

                // Enregistrer l'achat
                $user->purchasedEbooks()->attach($ebook->id, [
                    'amount' => $session->amount_total / 100, // Convertir en euros
                    'payment_id' => $session->payment_intent,
                ]);

                return view('payment.success', [
                    'ebook' => $ebook,
                    'payment_id' => $session->payment_intent,
                    'amount' => $session->amount_total / 100,
                ]);
            }

            return redirect()->route('public.ebooks.show', $ebook->slug)
                ->with('error', 'Le paiement n\'a pas été validé.');

        } catch (\Exception $e) {
            return redirect()->route('public.ebooks.show', $ebook->slug)
                ->with('error', 'Erreur lors de la vérification du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Gère l'annulation du paiement
     */
    public function cancel()
    {
        return view('payment.cancel');
    }

    /**
     * Webhook pour les événements Stripe
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('cashier.webhook.secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('Invalid signature', 400);
        }

        // Gérer l'événement
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                // Traiter le paiement réussi
                break;
            // ... gérer d'autres types d'événements
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);
    }
}
