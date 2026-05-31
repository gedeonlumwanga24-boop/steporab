<?php

namespace App\Services;

use App\DTOs\OrderData;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Produit;
use App\Models\Panier;
use App\Events\OrderPlaced;
use App\Events\OrderCancelled;
use App\Exceptions\InsufficientStockException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Crée une commande avec Pessimistic Locking
     *
     * @param OrderData $orderData
     * @return Commande
     * @throws InsufficientStockException
     */
    public function createOrder(OrderData $orderData): Commande
    {
        return DB::transaction(function () use ($orderData) {
            // Étape 1: Récupérer les articles du panier
            $items = $orderData->items ?: $this->cartService->getItems();

            if (empty($items)) {
                throw new \InvalidArgumentException('Panier vide: impossible de créer une commande');
            }

            // Étape 2: Extraire les IDs des produits
            $productIds = collect($items)
                ->pluck('product_id', 'id')
                ->values()
                ->unique()
                ->toArray();

            // Étape 3: Verrouiller les produits (Pessimistic Locking)
            // Cela empêche les autres transactions de modifier le stock pendant la création de la commande
            $products = Produit::whereIn('id', $productIds)
                ->lockForUpdate()  // 🔒 Pessimistic Lock
                ->get()
                ->keyBy('id');

            // Étape 4: Vérifier le stock disponible pour chaque article
            foreach ($items as $item) {
                $product = $products[$item['product_id']] ?? null;

                if (!$product) {
                    throw new \InvalidArgumentException(
                        "Produit {$item['product_id']} non trouvé"
                    );
                }

                if ($product->stock < $item['quantite']) {
                    throw new InsufficientStockException(
                        $product->id,
                        $item['quantite'],
                        $product->stock
                    );
                }
            }

            // Étape 5: Créer la commande
            $order = Commande::create([
                'user_id' => $orderData->userId,
                'total' => $orderData->total,
                'adresse' => $orderData->adresse,
                'statut' => 'en attente',  // Toujours initialiser en attente
            ]);

            // Étape 6: Créer les lignes de commande et décrémenter le stock
            foreach ($items as $item) {
                $product = $products[$item['product_id']];

                // Créer la ligne de commande
                CommandeProduit::create([
                    'commande_id' => $order->id,
                    'produit_id' => $product->id,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $product->prix,
                ]);

                // Décrémenter le stock
                $product->decrement('stock', $item['quantite']);
            }

            // Étape 7: Vider le panier de l'utilisateur
            if ($orderData->userId) {
                Panier::where('user_id', $orderData->userId)
                       ->where('status', Panier::STATUS_ACTIVE)
                       ->update(['status' => Panier::STATUS_CONVERTED]);
            }

            // Étape 8: Dispatcher l'événement
            event(new OrderPlaced($order));

            // Étape 9: Logger la création
            Log::info("Commande créée", [
                'order_id' => $order->id,
                'user_id' => $orderData->userId,
                'total' => $orderData->total,
                'items_count' => count($items),
            ]);

            return $order;

        }, attempts: 3);  // Réessayer jusqu'à 3 fois en cas de deadlock
    }

    /**
     * Annule une commande et restitue le stock
     *
     * @param int $orderId
     * @return Commande
     */
    public function cancelOrder(int $orderId): Commande
    {
        return DB::transaction(function () use ($orderId) {
            $order = Commande::with('produits')->findOrFail($orderId);

            // Ne pas annuler les commandes déjà livrées
            if ($order->statut === 'livrée') {
                throw new \InvalidArgumentException(
                    'Impossible d\'annuler une commande livrée'
                );
            }

            // Restituer le stock pour chaque produit
            foreach ($order->produits as $product) {
                $product->increment('stock', $product->pivot->quantite);
            }

            // Marquer la commande comme annulée
            $order->update(['statut' => 'annulée']);

            // Dispatcher l'événement
            event(new OrderCancelled($order));

            Log::info("Commande annulée", [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ]);

            return $order;

        }, attempts: 3);
    }

    /**
     * Obtient le détail d'une commande
     *
     * @param int $orderId
     * @param int|null $userId
     * @return Commande
     */
    public function getOrder(int $orderId, ?int $userId = null): Commande
    {
        $query = Commande::with(['produits', 'user']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->findOrFail($orderId);
    }

    /**
     * Récupère les commandes d'un utilisateur
     *
     * @param int $userId
     * @param string|null $status
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function getUserOrders(int $userId, ?string $status = null, int $perPage = 15)
    {
        $query = Commande::where('user_id', $userId)->latest();

        if ($status) {
            $query->where('statut', $status);
        }

        return $query->paginate($perPage);
    }

    /**
     * Met à jour le statut d'une commande
     *
     * @param int $orderId
     * @param string $newStatus
     * @return Commande
     */
    public function updateOrderStatus(int $orderId, string $newStatus): Commande
    {
        $order = Commande::findOrFail($orderId);

        // Valider les transitions de statut
        $validTransitions = [
            'en attente' => ['confirmée', 'annulée'],
            'confirmée' => ['expédiée', 'annulée'],
            'expédiée' => ['livrée'],
            'livrée' => [],
            'annulée' => [],
        ];

        $currentStatus = $order->statut;

        if (!isset($validTransitions[$currentStatus])) {
            throw new \InvalidArgumentException(
                "Statut actuel inconnu: {$currentStatus}"
            );
        }

        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            throw new \InvalidArgumentException(
                "Transition invalide de '{$currentStatus}' à '{$newStatus}'"
            );
        }

        // Si c'est une annulation, restituer le stock
        if ($newStatus === 'annulée' && $currentStatus !== 'annulée') {
            return $this->cancelOrder($orderId);
        }

        $order->update(['statut' => $newStatus]);

        Log::info("Statut de commande mis à jour", [
            'order_id' => $order->id,
            'old_status' => $currentStatus,
            'new_status' => $newStatus,
        ]);

        return $order;
    }

    /**
     * Récupère les statistiques des commandes
     *
     * @return array
     */
    public function getStats(): array
    {
        return [
            'total_orders' => Commande::count(),
            'pending_orders' => Commande::where('statut', 'en attente')->count(),
            'confirmed_orders' => Commande::where('statut', 'confirmée')->count(),
            'shipped_orders' => Commande::where('statut', 'expédiée')->count(),
            'delivered_orders' => Commande::where('statut', 'livrée')->count(),
            'cancelled_orders' => Commande::where('statut', 'annulée')->count(),
            'total_revenue' => Commande::where('statut', 'livrée')->sum('total'),
            'average_order_value' => Commande::avg('total'),
        ];
    }

    /**
     * Récupère toutes les commandes (pour l'admin)
     *
     * @param string|null $status
     * @param string|null $sortBy
     * @param int $perPage
     * @return \Illuminate\Pagination\Paginator
     */
    public function getAllOrders(?string $status = null, ?string $sortBy = 'latest', int $perPage = 20)
    {
        $query = Commande::with(['user', 'produits']);

        if ($status) {
            $query->where('statut', $status);
        }

        // Tri
        match ($sortBy) {
            'oldest' => $query->oldest(),
            'total_asc' => $query->orderBy('total', 'asc'),
            'total_desc' => $query->orderBy('total', 'desc'),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }
}
