<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    /**
     * Créer une nouvelle commande
     */
    public function createOrder(int $userId, array $data): Order
    {
        return Order::create([
            'user_id' => $userId,
            'total' => $data['total'] ?? 0,
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Ajouter des produits à une commande
     */
    public function addProductsToOrder(int $orderId, array $products): void
    {
        foreach ($products as $product) {
            OrderProduct::create([
                'order_id' => $orderId,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price_unit' => $product['price'] ?? 0,
            ]);
        }
    }

    /**
     * Récupérer les commandes d'un utilisateur
     */
    public function getUserOrders(int $userId): Collection
    {
        return Order::where('user_id', $userId)->get();
    }

    /**
     * Récupérer une commande par ID
     */
    public function getOrderById(int $id): ?Order
    {
        return Order::with('products')->find($id);
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateOrderStatus(int $id, string $status): bool
    {
        $order = Order::find($id);
        if (!$order) return false;
        return $order->update(['status' => $status]);
    }

    /**
     * Obtenir toutes les commandes
     */
    public function getAllOrders(): Collection
    {
        return Order::all();
    }
}
