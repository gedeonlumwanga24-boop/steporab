<?php

namespace App\Actions\Product;

use App\DTOs\ProductData;
use App\Models\Produit;

class CreateProduct
{
    public function execute(ProductData $data): Produit
    {
        // Execute the creation logic
        return new Produit();
    }
}
