<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Commande;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class PaymentService
{
    public function gateway(?string $name = null): PaymentGatewayInterface
    {
        $name = $name ?? config('payments.default');
        $config = config("payments.gateways.{$name}");

        if (!$config || !isset($config['driver'])) {
            throw new InvalidArgumentException("Passerelle de paiement inconnue : {$name}");
        }

        return app($config['driver']);
    }

    public function processPayment(int $orderId, string $method, array $metadata = []): array
    {
        $order = Commande::findOrFail($orderId);
        $gateway = $this->gateway($method);

        $result = $gateway->charge((float) $order->total, array_merge($metadata, [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]));

        if ($result['success']) {
            $order->update(['statut' => 'confirmée']);
        }

        Log::info('Paiement traité', [
            'order_id' => $order->id,
            'method' => $method,
            'success' => $result['success'],
            'transaction_id' => $result['transaction_id'] ?? null,
        ]);

        return $result;
    }

    public function refundPayment(int $orderId, string $method, string $transactionId): array
    {
        $order = Commande::findOrFail($orderId);
        $gateway = $this->gateway($method);

        return $gateway->refund($transactionId, (float) $order->total);
    }
}
