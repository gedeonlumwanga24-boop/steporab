<?php

namespace App\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Charge the customer for the given amount.
     *
     * @return array{success: bool, transaction_id: ?string, message: string}
     */
    public function charge(float $amount, array $metadata = []): array;

    /**
     * Refund a previous transaction.
     *
     * @return array{success: bool, refund_id: ?string, message: string}
     */
    public function refund(string $transactionId, float $amount): array;

    /**
     * Verify webhook signature from the payment provider.
     */
    public function verifyWebhook(string $payload, string $signature): bool;
}
