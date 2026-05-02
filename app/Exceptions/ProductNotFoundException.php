<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception levée quand un produit n'est pas trouvé
 */
class ProductNotFoundException extends Exception
{
    public function __construct(int $productId)
    {
        parent::__construct("Le produit avec l'ID {$productId} n'existe pas.");
    }
}
