<?php

namespace App\Actions\Payment;

use App\Services\PaymentService;

class ProcessPayment
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function execute(int $orderId, string $method, array $metadata = []): array
    {
        return $this->paymentService->processPayment($orderId, $method, $metadata);
    }
}
