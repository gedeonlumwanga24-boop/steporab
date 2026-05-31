<?php

namespace App\Actions\Product;

use App\Models\Produit;

class DeleteProduct
{
    public function execute(Produit $product): bool
    {
        // Execute the deletion logic
        return true;
    }
}
