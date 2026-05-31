<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\CartItemData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\AddToCartRequest;
use App\Http\Resources\Api\V1\CartResource;
use App\Services\CartService;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends ApiController
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * GET /api/v1/cart
     * Récupère le panier courant
     */
    public function index(): JsonResponse
    {
        $cartInfo = $this->cartService->getCartInfo();

        return $this->success([
            'items' => $cartInfo['items'],
            'total' => $cartInfo['total'],
            'count' => $cartInfo['count'],
            'is_empty' => $cartInfo['is_empty'],
        ]);
    }

    /**
     * POST /api/v1/cart/items
     * Ajoute un article au panier
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        try {
            $dto = CartItemData::fromRequest($request->validated());
            $this->cartService->addItem($dto);

            $cartInfo = $this->cartService->getCartInfo();

            return $this->created([
                'items' => $cartInfo['items'],
                'total' => $cartInfo['total'],
                'count' => $cartInfo['count'],
                'message' => 'Produit ajouté au panier avec succès',
            ]);

        } catch (ProductNotFoundException $e) {
            return $this->notFound($e->getMessage());
        } catch (InsufficientStockException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de l\'ajout au panier: ' . $e->getMessage(), 500);
        }
    }

    /**
     * PATCH /api/v1/cart/items/{id}
     * Met à jour la quantité d'un article
     */
    public function update(Request $request, int $productId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantite' => 'required|integer|min:0|max:999',
                'taille' => 'nullable|string|max:50',
            ]);

            $this->cartService->updateQuantity(
                $productId,
                $validated['quantite'],
                $validated['taille'] ?? null
            );

            $cartInfo = $this->cartService->getCartInfo();

            return $this->success([
                'items' => $cartInfo['items'],
                'total' => $cartInfo['total'],
                'count' => $cartInfo['count'],
                'message' => 'Article mis à jour',
            ]);

        } catch (InsufficientStockException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->error('Erreur lors de la mise à jour: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/v1/cart/items/{id}
     * Supprime un article du panier
     */
    public function destroy(Request $request, int $productId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'taille' => 'nullable|string|max:50',
            ]);

            $this->cartService->removeItem($productId, $validated['taille'] ?? null);

            $cartInfo = $this->cartService->getCartInfo();

            return $this->success([
                'items' => $cartInfo['items'],
                'total' => $cartInfo['total'],
                'count' => $cartInfo['count'],
                'is_empty' => $cartInfo['is_empty'],
                'message' => 'Article supprimé du panier',
            ]);

        } catch (\Exception $e) {
            return $this->error('Erreur lors de la suppression: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/v1/cart
     * Vide complètement le panier
     */
    public function clear(): JsonResponse
    {
        try {
            $this->cartService->empty();

            return $this->success([
                'items' => [],
                'total' => 0,
                'count' => 0,
                'message' => 'Panier vidé',
            ]);

        } catch (\Exception $e) {
            return $this->error('Erreur lors du vidage du panier: ' . $e->getMessage(), 500);
        }
    }
}
