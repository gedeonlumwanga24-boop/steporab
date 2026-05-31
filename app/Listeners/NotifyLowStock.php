<?php

namespace App\Listeners;

use App\Events\ProductOutOfStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyLowStock implements ShouldQueue
{
    public function handle(ProductOutOfStock $event): void
    {
        Log::warning('Produit en rupture de stock', [
            'product_id' => $event->product->id,
            'product_name' => $event->product->nom,
            'stock' => $event->product->stock,
        ]);
    }
}
