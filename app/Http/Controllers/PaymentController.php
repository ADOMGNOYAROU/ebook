<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\User;
use App\Services\PayGateGlobalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected PayGateGlobalService $paygate;

    public function __construct(PayGateGlobalService $paygate)
    {
        $this->paygate = $paygate;
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
     * Traite le paiement via PayGateGlobal (Méthode 2 - Redirection)
     */
    public function processPayment(Request $request, $ebookId)
    {
        $user = Auth::user();
        $ebook = Ebook::findOrFail($ebookId);

        // Validation
        $validated = $request->validate([
            'phone' => 'required|string|min:8|max:15',
            'network' => 'required|in:FLOOZ,MOOV,TOGOCEL,TMONEY',
        ]);

        try {
            // Créer l'identifiant unique de transaction
            $identifier = 'ebook_' . $ebook->id . '_' . $user->id . '_' . time();

            // Générer l'URL de paiement PayGateGlobal
            $paymentUrl = $this->paygate->createPaymentPage([
                'amount' => $ebook->price ?? 9.99,
                'description' => 'Achat: ' . $ebook->title,
                'identifier' => $identifier,
                'return_url' => route('payment.success') . '?identifier=' . $identifier . '&ebook_id=' . $ebook->id,
                'phone' => $request->phone,
                'network' => $request->network,
            ]);

            if (!$paymentUrl) {
                throw new \Exception('Impossible de créer la page de paiement');
            }

            // Sauvegarder l'identifiant en session pour vérification
            session(['payment_identifier' => $identifier]);

            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Gère le retour réussi du paiement
     */
    public function success(Request $request)
    {
        $identifier = $request->get('identifier');
        $ebookId = $request->get('ebook_id');
        
        if (!$identifier || !$ebookId) {
            return redirect()->route('public.ebooks.index')
                ->with('error', 'Paiement invalide.');
        }

        try {
            // Vérifier le statut du paiement
            $status = $this->paygate->checkPaymentStatusByIdentifier($identifier);

            if (!$status || !isset($status['status']) || $status['status'] != '0') {
                return redirect()->route('public.ebooks.show', $ebookId)
                    ->with('error', 'Le paiement n\'a pas été validé. Statut: ' . ($status['status'] ?? 'inconnu'));
            }

            $user = Auth::user();
            $ebook = Ebook::findOrFail($ebookId);

            // Enregistrer l'achat
            $user->purchasedEbooks()->attach($ebook->id, [
                'amount' => $status['amount'] ?? $ebook->price ?? 9.99,
                'payment_id' => $status['tx_reference'] ?? $status['payment_reference'] ?? uniqid('PAY_'),
                'payment_method' => $status['payment_method'] ?? 'FLOOZ',
                'created_at' => now(),
            ]);

            return view('payment.success', [
                'ebook' => $ebook,
                'amount' => $status['amount'] ?? $ebook->price ?? 9.99,
                'payment_method' => $status['payment_method'] ?? 'FLOOZ',
            ]);

        } catch (\Exception $e) {
            return redirect()->route('public.ebooks.show', $ebookId)
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Gère l'annulation du paiement
     */
    public function cancel(Request $request)
    {
        $ebookId = $request->get('ebook_id');
        return view('payment.cancel', ['ebook_id' => $ebookId]);
    }

    /**
     * Webhook pour les notifications PayGateGlobal
     */
    public function webhook(Request $request)
    {
        try {
            $data = $request->all();

            // Vérifier que c'est une confirmation de paiement
            if (!isset($data['tx_reference']) || !isset($data['identifier'])) {
                return response('Invalid payload', 400);
            }

            // Trouver le paiement correspondant
            // Vous pouvez ici mettre à jour le statut dans votre base de données
            Log::info('PayGateGlobal webhook received', $data);

            return response('', 200);
        } catch (\Exception $e) {
            Log::error('PayGateGlobal webhook error', ['error' => $e->getMessage()]);
            return response('Error', 500);
        }
    }
}
