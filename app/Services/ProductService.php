<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Models\Produit;

class ProductService
{
    /**
     * @param ProductData $data
     * @return Produit
     */
    public function createProduct(ProductData $data): Produit
    {
        // To be implemented using Repositories and Actions
        return new Produit();
    }

    /**
     * @param int $id
     * @param ProductData $data
     * @return bool
     */
    public function updateProduct(int $id, ProductData $data): bool
    {
        // To be implemented
        return true;
    }
}
