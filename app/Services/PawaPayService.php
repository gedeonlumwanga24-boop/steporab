<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Commande;

class PawaPayService
{
    private string $baseUrl;
    private string $apiToken;
    private string $currency;
    private float  $feePercentage;
    private string $callbackUrl;

    public function __construct()
    {
        // base_url = https://api.sandbox.pawapay.io (sans slash ni chemin)
        $this->baseUrl       = rtrim(config('pawapay.base_url'), '/');
        $this->apiToken      = config('pawapay.api_token');
        $this->currency      = config('pawapay.currency', 'CDF');
        $this->feePercentage = (float) config('pawapay.fee_percentage', 2.0);
        $this->callbackUrl   = config('pawapay.callback_url');
    }

    /**
     * Calcule le montant total incluant les frais PawaPay (cachés au client).
     * Exemple : commande 10 000 CDF + 2% frais = 10 200 CDF envoyés à PawaPay.
     */
    public function computeAmountWithFees(float $amount): string
    {
        $withFees = $amount * (1 + $this->feePercentage / 100);
        // PawaPay requiert un montant en string entier pour CDF
        return (string) round($withFees);
    }

    /**
     * Retourne un client HTTP pré-configuré pour PawaPay.
     * withoutVerifying() est nécessaire en local (XAMPP / SSL auto-signé).
     */
    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withoutVerifying()
            ->withToken($this->apiToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])
            ->timeout(30);
    }

    /**
     * Initier un dépôt (paiement entrant du client vers Stepora).
     * Appelle POST /v1/deposits sur l'API PawaPay.
     *
     * @param  Commande $commande   La commande à payer
     * @param  string   $phone      Numéro international (ex: 243812345678)
     * @param  string   $provider   Code opérateur PawaPay (ex: VODACOM_MPESA_COD)
     * @return array{success: bool, depositId: ?string, message: string}
     */
    public function initiateDeposit(Commande $commande, string $phone, string $provider): array
    {
        // ID unique côté Stepora pour cette transaction (UUID v4)
        $depositId      = (string) Str::uuid();
        $amountWithFees = $this->computeAmountWithFees((float) $commande->total);

        $payload = [
            'depositId'            => $depositId,
            'returnUrl'            => route('commande.paiement.success', $commande->id),
            'amount'               => $amountWithFees,
            'currency'             => $this->currency,
            'correspondent'        => $provider,
            'payer'                => [
                'type'    => 'MSISDN',
                'address' => ['value' => $phone],
            ],
            'customerTimestamp'    => now()->toIso8601String(),
            'statementDescription' => 'Stepora ' . $commande->id,
        ];

        // Ajouter le callback URL si configuré (ngrok ou production)
        if ($this->callbackUrl && !str_contains($this->callbackUrl, 'VOTRE_URL_NGROK')) {
            $payload['depositCallbackUrl'] = $this->callbackUrl;
        }

        Log::info('PawaPay initiateDeposit → payload', [
            'url'         => $this->baseUrl . '/v1/deposits',
            'provider'    => $provider,
            'phone'       => $phone,
            'amount'      => $amountWithFees,
            'callbackUrl' => $payload['depositCallbackUrl'] ?? 'non configuré',
        ]);

        try {
            $response = $this->http()->post($this->baseUrl . '/v1/deposits', $payload);

            Log::info('PawaPay initiateDeposit ← réponse', [
                'http_status' => $response->status(),
                'body'        => $response->json(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'ACCEPTED') {
                    $commande->update([
                        'pawapay_deposit_id'    => $depositId,
                        'mobile_money_provider' => $provider,
                        'mobile_money_number'   => $phone,
                        'payment_method'        => $this->getMethodFromProvider($provider),
                        'payment_status'        => Commande::PAY_EN_VERIF,
                    ]);

                    Log::info('PawaPay deposit ACCEPTED', [
                        'commande_id' => $commande->id,
                        'depositId'   => $depositId,
                        'provider'    => $provider,
                        'amount'      => $amountWithFees,
                    ]);

                    return [
                        'success'   => true,
                        'depositId' => $depositId,
                        'message'   => 'Paiement initié. Veuillez confirmer sur votre téléphone.',
                    ];
                }

                $rejectionCode = $data['rejectionReason']['rejectionCode'] ?? ($data['failureReason']['failureCode'] ?? 'UNKNOWN');
                $rejectionMsg  = $data['rejectionReason']['rejectionMessage'] ?? ($data['failureReason']['failureMessage'] ?? 'Erreur inconnue');

                Log::warning('PawaPay deposit REJECTED', [
                    'response'    => $data,
                    'commande_id' => $commande->id,
                ]);

                return [
                    'success'   => false,
                    'depositId' => null,
                    'message'   => 'Paiement refusé : ' . $rejectionCode . ' — ' . $rejectionMsg,
                ];
            }

            Log::error('PawaPay HTTP error on initiateDeposit', [
                'http_status' => $response->status(),
                'body'        => $response->body(),
                'commande_id' => $commande->id,
            ]);

            return [
                'success'   => false,
                'depositId' => null,
                'message'   => 'Erreur de communication avec PawaPay (HTTP ' . $response->status() . ').',
            ];

        } catch (\Exception $e) {
            Log::error('PawaPay exception on initiateDeposit', [
                'error'       => $e->getMessage(),
                'commande_id' => $commande->id,
            ]);

            return [
                'success'   => false,
                'depositId' => null,
                'message'   => 'Erreur réseau. Vérifiez votre connexion et réessayez.',
            ];
        }
    }

    /**
     * Vérifier l'état d'un dépôt (polling toutes les 5s depuis la page d'attente).
     * Appelle GET /v1/deposits/{depositId}
     *
     * @return array{status: string, failureCode: ?string}
     */
    public function checkDepositStatus(string $depositId): array
    {
        try {
            $response = $this->http()
                ->timeout(15)
                ->get($this->baseUrl . '/v1/deposits/' . $depositId);

            if ($response->successful()) {
                $body = $response->json();

                // PawaPay retourne un TABLEAU : [{depositId, status, ...}]
                // On prend le premier élément
                $data = is_array($body) && isset($body[0]) ? $body[0] : $body;

                $status = $data['status'] ?? 'UNKNOWN';

                Log::info('PawaPay polling response', [
                    'depositId' => $depositId,
                    'status'    => $status,
                ]);

                // Statuts: ACCEPTED | PROCESSING | COMPLETED | FAILED | CANCELLED | TIMED_OUT | REJECTED
                return [
                    'status'      => $status,
                    'failureCode' => $data['failureReason']['failureCode'] ?? null,
                ];
            }

            Log::error('PawaPay polling HTTP error', [
                'http_status' => $response->status(),
                'body'        => $response->body(),
            ]);

            return ['status' => 'ERROR', 'failureCode' => null];

        } catch (\Exception $e) {
            Log::error('PawaPay checkDepositStatus error', ['error' => $e->getMessage()]);
            return ['status' => 'ERROR', 'failureCode' => null];
        }
    }

    /**
     * Récupère la configuration active du compte PawaPay.
     * Utile pour vérifier quels opérateurs sont activés sur votre compte.
     */
    public function getActiveConfiguration(): array
    {
        try {
            $response = $this->http()
                ->timeout(15)
                ->get($this->baseUrl . '/v1/active-configuration');

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'error' => 'HTTP ' . $response->status() . ' : ' . $response->body()];

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Convertit le code opérateur PawaPay en nom court pour la DB.
     */
    public function getMethodFromProvider(string $provider): string
    {
        return match ($provider) {
            'VODACOM_MPESA_COD' => 'mpesa',
            'AIRTEL_COD'        => 'airtel_money',
            'ORANGE_COD'        => 'orange_money',
            default             => 'mobile_money',
        };
    }

    /**
     * Détecte automatiquement l'opérateur à partir du préfixe du numéro.
     * Retourne null si le préfixe est inconnu.
     */
    public function detectProviderFromPhone(string $phone): ?string
    {
        // Normaliser en format local (0XXXXXXXXX)
        $local   = preg_replace('/^(\+?243|00243)/', '0', $phone);
        $prefix3 = substr($local, 0, 3);

        foreach (config('pawapay.providers', []) as $code => $info) {
            if (in_array($prefix3, $info['prefix'])) {
                return $code;
            }
        }

        return null;
    }
}
