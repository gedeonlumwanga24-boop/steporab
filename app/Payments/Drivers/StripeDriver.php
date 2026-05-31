<?php

namespace App\Payments\Drivers;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class StripeDriver implements PaymentGatewayInterface
{
    public function charge(float $amount, array $metadata = []): array
    {
        // Production: integrate Stripe SDK (Stripe\StripeClient)
        return [
            'success' => true,
            'transaction_id' => 'stripe_' . Str::uuid(),
            'message' => 'Paiement Stripe simulé avec succès.',
        ];
    }

    public function refund(string $transactionId, float $amount): array
    {
        return [
            'success' => true,
            'refund_id' => 'stripe_refund_' . Str::uuid(),
            'message' => 'Remboursement Stripe simulé.',
        ];
    }

    public function verifyWebhook(string $payload, string $signature): bool
    {
        return !empty($signature);
    }
}
