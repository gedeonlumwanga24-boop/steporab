<?php

namespace App\Actions\Cart;

use App\DTOs\CartItemData;

class AddProductToCart
{
    public function execute(CartItemData $item)
    {
        // Execute logic to add product to cart (session or DB depending on user context)
    }
}
