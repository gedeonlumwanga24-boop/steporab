<?php

namespace App\Repositories\Eloquent;

use App\DTOs\CartItemData;
use App\DTOs\OrderData;
use App\Models\Commande;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Commande::with(['user', 'produits'])->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Commande
    {
        return Commande::with(['user', 'produits'])->find($id);
    }

    public function findForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Commande::with('produits')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function create(OrderData $data): Commande
    {
        /** @var Commande $order */
        $order = Commande::create([
            'user_id' => $data->userId,
            'total'   => $data->total,
            'adresse' => $data->adresse,
            'statut'  => $data->statut,
        ]);

        // Attach each cart item to the order with pivot data
        foreach ($data->items as $item) {
            /** @var CartItemData $item */
            $order->produits()->attach($item->productId, [
                'quantite'      => $item->quantite,
                'prix_unitaire' => 0, // Will be computed by the OrderService with lockForUpdate()
            ]);
        }

        return $order->load('produits');
    }

    public function updateStatus(Commande $order, string $statut): Commande
    {
        $order->update(['statut' => $statut]);

        return $order->fresh();
    }

    public function cancel(Commande $order): bool
    {
        return (bool) $order->update(['statut' => 'annulée']);
    }
}
