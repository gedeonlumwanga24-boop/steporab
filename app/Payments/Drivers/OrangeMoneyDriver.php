<?php

namespace App\Payments\Drivers;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class OrangeMoneyDriver implements PaymentGatewayInterface
{
    public function charge(float $amount, array $metadata = []): array
    {
        return [
            'success' => true,
            'transaction_id' => 'om_' . Str::uuid(),
            'message' => 'Paiement Orange Money simulé avec succès.',
        ];
    }

    public function refund(string $transactionId, float $amount): array
    {
        return [
            'success' => false,
            'refund_id' => null,
            'message' => 'Remboursement Orange Money non supporté via API.',
        ];
    }

    public function verifyWebhook(string $payload, string $signature): bool
    {
        return !empty($signature);
    }
}
