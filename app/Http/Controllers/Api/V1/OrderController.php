<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\CartItemData;
use App\DTOs\OrderData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Requests\Api\V1\UpdateOrderRequest;
use App\Http\Resources\Api\V1\OrderCollection;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Produit;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends ApiController
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders
    ) {}

    /**
     * GET /api/v1/orders
     * Auth user sees their own orders; admin sees all
     */
    public function index(): JsonResponse
    {
        $user   = auth()->user();
        $orders = $this->orders->findForUser($user->id);

        return $this->success(new OrderCollection($orders));
    }

    /**
     * GET /api/v1/orders/{id}
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orders->findById($id);

        if (! $order) {
            return $this->notFound('Commande introuvable.');
        }

        $this->authorize('view', $order);

        return $this->success(new OrderResource($order));
    }

    /**
     * POST /api/v1/orders
     * Creates order using DB::transaction + lockForUpdate to prevent overselling
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $user     = auth()->user();
        $itemsRaw = $request->validated()['items'];

        try {
            $order = DB::transaction(function () use ($request, $user, $itemsRaw) {

                // 1. Extract product IDs
                $productIds = array_column($itemsRaw, 'product_id');

                // 2. Lock rows to prevent concurrent stock race conditions
                $products = Produit::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // 3. Validate stock for each item
                $items    = [];
                $total    = 0;

                foreach ($itemsRaw as $raw) {
                    $product  = $products[$raw['product_id']] ?? null;
                    $quantite = (int) $raw['quantite'];

                    if (! $product) {
                        throw new \RuntimeException("Produit #{$raw['product_id']} introuvable.");
                    }

                    if ($product->stock < $quantite) {
                        throw new \RuntimeException(
                            "Stock insuffisant pour \"{$product->nom}\". Disponible: {$product->stock}."
                        );
                    }

                    $total  += $product->prix * $quantite;
                    $items[] = new CartItemData(
                        productId: $product->id,
                        quantite: $quantite,
                        taille: $raw['taille'] ?? null,
                    );

                    // 4. Decrement stock immediately inside transaction
                    $product->decrement('stock', $quantite);
                }

                // 5. Build DTO and persist
                $dto = new OrderData(
                    userId: $user->id,
                    total: $total,
                    adresse: $request->validated()['adresse'],
                    items: $items,
                );

                return $this->orders->create($dto);
            });

            // 6. Clear cart after successful order
            session()->forget('cart');

            // 7. Log business event
            Log::channel('orders')->info('Order created', [
                'order_id' => $order->id,
                'user_id'  => $user->id,
                'total'    => $order->total,
            ]);

            return $this->created(new OrderResource($order), 'Commande créée avec succès.');

        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Throwable $e) {
            Log::channel('orders')->error('Order creation failed', ['error' => $e->getMessage()]);

            return $this->error('Une erreur est survenue. Veuillez réessayer.', 500);
        }
    }

    /**
     * PATCH /api/v1/orders/{id}
     * Admin/Manager updates order status
     */
    public function updateStatus(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orders->findById($id);

        if (! $order) {
            return $this->notFound('Commande introuvable.');
        }

        $updated = $this->orders->updateStatus($order, $request->validated()['statut']);

        return $this->success(new OrderResource($updated), 'Statut mis à jour.');
    }
}
