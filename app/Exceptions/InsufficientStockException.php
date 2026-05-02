<?php

namespace App\Exceptions;

use Exception;

/**
 * Exception levée quand le stock est insuffisant
 */
class InsufficientStockException extends Exception
{
    public function __construct(int $productId, int $requested, int $available)
    {
        parent::__construct("Stock insuffisant pour le produit {$productId}. Demandé: {$requested}, Disponible: {$available}");
    }
}
