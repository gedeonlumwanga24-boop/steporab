<?php

namespace App\Actions\Order;

use App\DTOs\OrderData;
use App\Models\Commande;

class CreateOrder
{
    public function execute(OrderData $data): Commande
    {
        // Logic to create order in database
        return new Commande();
    }
}
