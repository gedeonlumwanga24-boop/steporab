<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Pagination\Paginator;

/**
 * Classe helper pour les opérations courantes
 */
class AppHelper
{
    /**
     * Formater un montant en devise (EUR)
     */
    public static function formatPrice(float $price, string $currency = '€'): string
    {
        return number_format($price, 2, ',', ' ') . ' ' . $currency;
    }

    /**
     * Obtenir le prix total du panier d'un utilisateur
     */
    public static function getCartTotal(int $userId): float
    {
        $cart = Cart::where('user_id', $userId)->first();
        return $cart ? $cart->getTotal() : 0.0;
    }

    /**
     * Compter les produits dans le panier
     */
    public static function getCartItemCount(int $userId): int
    {
        $cart = Cart::where('user_id', $userId)->first();
        return $cart ? $cart->items()->count() : 0;
    }

    /**
     * Obtenir les catégories avec leurs comptes de produits
     */
    public static function getCategoriesWithCount()
    {
        return \App\Models\Category::withCount('produits')->get();
    }

    /**
     * Obtenir les produits en rupture de stock
     */
    public static function getOutOfStockProducts()
    {
        return Product::where('stock', '<=', 0)->get();
    }

    /**
     * Générer un slug à partir d'une chaîne
     */
    public static function generateSlug(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = preg_replace('~-+~', '-', $text);
        $text = trim($text, '-');
        return strtolower($text);
    }

    /**
     * Tronquer du texte avec ellipsis
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Obtenir l'initiale d'un nom
     */
    public static function getInitials(string $name): string
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= substr($word, 0, 1);
        }
        return strtoupper($initials);
    }

    /**
     * Obtenir les chiffres clés du tableau de bord
     */
    public static function getDashboardStats(): array
    {
        return [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::sum('total'),
        ];
    }
}
