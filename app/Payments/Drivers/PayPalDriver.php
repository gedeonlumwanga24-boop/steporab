<?php

namespace App\Payments\Drivers;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class PayPalDriver implements PaymentGatewayInterface
{
    public function charge(float $amount, array $metadata = []): array
    {
        return [
            'success' => true,
            'transaction_id' => 'paypal_' . Str::uuid(),
            'message' => 'Paiement PayPal simulé avec succès.',
        ];
    }

    public function refund(string $transactionId, float $amount): array
    {
        return [
            'success' => true,
            'refund_id' => 'paypal_refund_' . Str::uuid(),
            'message' => 'Remboursement PayPal simulé.',
        ];
    }

    public function verifyWebhook(string $payload, string $signature): bool
    {
        return !empty($signature);
    }
}
