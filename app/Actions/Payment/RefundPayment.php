<?php

namespace App\Actions\Payment;

use App\Services\PaymentService;

class RefundPayment
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function execute(int $orderId, string $method, string $transactionId): array
    {
        return $this->paymentService->refundPayment($orderId, $method, $transactionId);
    }
}
