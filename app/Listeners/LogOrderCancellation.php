<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogOrderCancellation implements ShouldQueue
{
    public function handle(OrderCancelled $event): void
    {
        Log::info('Commande annulée (event)', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'total' => $event->order->total,
        ]);
    }
}
