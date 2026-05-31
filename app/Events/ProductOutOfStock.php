<?php

namespace App\Events;

use App\Models\Produit;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductOutOfStock
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Produit $product) {}
}
