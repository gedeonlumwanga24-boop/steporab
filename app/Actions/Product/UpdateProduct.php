<?php

namespace App\Actions\Product;

use App\DTOs\ProductData;
use App\Models\Produit;

class UpdateProduct
{
    public function execute(Produit $product, ProductData $data): bool
    {
        // Execute the update logic
        return true;
    }
}
