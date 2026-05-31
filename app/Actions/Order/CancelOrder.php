<?php

namespace App\Actions\Order;

use App\Models\Commande;

class CancelOrder
{
    public function execute(Commande $order): bool
    {
        // Logic to cancel order and return stock
        return true;
    }
}
