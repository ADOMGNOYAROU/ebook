<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayGateGlobalService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.paygateglobal.api_key', '');
        $this->baseUrl = 'https://paygateglobal.com';
    }

    /**
     * Crée une page de paiement PayGateGlobal (Méthode 2 - Redirection)
     */
    public function createPaymentPage(array $data): ?string
    {
        try {
            $params = [
                'token' => $this->apiKey,
                'amount' => $data['amount'],
                'description' => $data['description'] ?? 'Achat e-book',
                'identifier' => $data['identifier'] ?? uniqid('ebook_'),
                'url' => $data['return_url'],
            ];

            // Paramètres optionnels
            if (!empty($data['phone'])) {
                $params['phone'] = $data['phone'];
            }
            if (!empty($data['network'])) {
                $params['network'] = $data['network'];
            }

            $query = http_build_query($params);
            $paymentUrl = $this->baseUrl . '/v1/page?' . $query;

            return $paymentUrl;

        } catch (\Exception $e) {
            Log::error('PayGateGlobal payment page error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Demande de paiement direct (Méthode 1 - API POST)
     */
    public function requestPayment(array $data): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/api/v1/pay', [
                'auth_token' => $this->apiKey,
                'phone_number' => $data['phone_number'],
                'amount' => $data['amount'],
                'description' => $data['description'] ?? 'Achat e-book',
                'identifier' => $data['identifier'] ?? uniqid('ebook_'),
                'network' => $data['network'] ?? 'FLOOZ',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayGateGlobal payment request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayGateGlobal service error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Vérifie le statut d'un paiement (v1 avec tx_reference)
     */
    public function checkPaymentStatus(string $txReference): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/api/v1/status', [
                'auth_token' => $this->apiKey,
                'tx_reference' => $txReference,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PayGateGlobal status check error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Vérifie le statut d'un paiement (v2 avec identifier)
     */
    public function checkPaymentStatusByIdentifier(string $identifier): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/api/v2/status', [
                'auth_token' => $this->apiKey,
                'identifier' => $identifier,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PayGateGlobal status check error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Consulte le solde des comptes Flooz et TMoney
     */
    public function checkBalance(): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/api/v1/check-balance', [
                'auth_token' => $this->apiKey,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PayGateGlobal balance check error', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
