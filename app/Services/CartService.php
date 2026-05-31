<?php

namespace App\Services;

use App\DTOs\CartItemData;
use App\Models\Panier;
use App\Models\PanierItem;
use App\Models\Produit;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_CART_KEY = 'cart_items';
    protected const SESSION_CART_TOTAL = 'cart_total';
    protected ?Panier $cartModel = null;

    /**
     * Ajoute un article au panier
     *
     * @param CartItemData $itemData
     * @return void
     * @throws ProductNotFoundException
     * @throws InsufficientStockException
     */
    public function addItem(CartItemData $itemData): void
    {
        $product = Produit::find($itemData->productId);

        if (! $product) {
            throw new ProductNotFoundException($itemData->productId);
        }

        if ($product->stock < $itemData->quantite) {
            throw new InsufficientStockException(
                $product->id,
                $itemData->quantite,
                $product->stock
            );
        }

        if (Auth::check()) {
            $this->addItemToDatabase($itemData, $product);
        } else {
            $this->addItemToSession($itemData, $product);
        }
    }

    /**
     * Ajoute un article à la base de données (utilisateur authentifié)
     */
    protected function addItemToDatabase(CartItemData $itemData, Produit $product): void
    {
        $cart = $this->getCart();

        $existingItem = $cart->items()
            ->where('produit_id', $itemData->productId)
            ->where('taille', $itemData->taille)
            ->first();

        if ($existingItem) {
            // Vérifier que l'augmentation de quantité ne dépasse pas le stock
            $totalQuantity = $existingItem->quantite + $itemData->quantite;
            if ($totalQuantity > $product->stock) {
                throw new InsufficientStockException(
                    $product->id,
                    $totalQuantity,
                    $product->stock
                );
            }

            $existingItem->updateQuantity($totalQuantity);
        } else {
            PanierItem::create([
                'panier_id' => $cart->id,
                'produit_id' => $itemData->productId,
                'quantite' => $itemData->quantite,
                'prix_unitaire' => $product->prix,
                'taille' => $itemData->taille,
            ]);

            $cart->updateTotal();
        }
    }

    /**
     * Ajoute un article à la session (utilisateur non authentifié)
     */
    protected function addItemToSession(CartItemData $itemData, Produit $product): void
    {
        $cartItems = Session::get(self::SESSION_CART_KEY, []);

        // Créer une clé unique pour l'article (produit + taille)
        $itemKey = $itemData->productId . '_' . ($itemData->taille ?? 'none');

        if (isset($cartItems[$itemKey])) {
            // Augmenter la quantité
            $newQuantity = $cartItems[$itemKey]['quantite'] + $itemData->quantite;

            if ($newQuantity > $product->stock) {
                throw new InsufficientStockException(
                    $product->id,
                    $newQuantity,
                    $product->stock
                );
            }

            $cartItems[$itemKey]['quantite'] = $newQuantity;
        } else {
            // Ajouter le nouvel article
            $cartItems[$itemKey] = [
                'product_id' => $itemData->productId,
                'quantite' => $itemData->quantite,
                'prix_unitaire' => $product->prix,
                'taille' => $itemData->taille,
                'nom' => $product->nom,
                'image' => $product->image,
            ];
        }

        Session::put(self::SESSION_CART_KEY, $cartItems);
        $this->updateSessionTotal();
    }

    /**
     * Supprime un article du panier
     */
    public function removeItem(int $productId, ?string $taille = null): void
    {
        if (Auth::check()) {
            $this->removeItemFromDatabase($productId, $taille);
        } else {
            $this->removeItemFromSession($productId, $taille);
        }
    }

    /**
     * Supprime un article de la base de données
     */
    protected function removeItemFromDatabase(int $productId, ?string $taille = null): void
    {
        $cart = $this->getCart();

        $query = $cart->items()->where('produit_id', $productId);

        if ($taille) {
            $query->where('taille', $taille);
        }

        $query->delete();
        $cart->updateTotal();
    }

    /**
     * Supprime un article de la session
     */
    protected function removeItemFromSession(int $productId, ?string $taille = null): void
    {
        $itemKey = $productId . '_' . ($taille ?? 'none');
        $cartItems = Session::get(self::SESSION_CART_KEY, []);

        unset($cartItems[$itemKey]);

        Session::put(self::SESSION_CART_KEY, $cartItems);
        $this->updateSessionTotal();
    }

    /**
     * Met à jour la quantité d'un article
     */
    public function updateQuantity(int $productId, int $quantity, ?string $taille = null): void
    {
        if ($quantity < 0) {
            return;
        }

        if ($quantity === 0) {
            $this->removeItem($productId, $taille);
            return;
        }

        $product = Produit::find($productId);

        if (! $product) {
            throw new ProductNotFoundException($productId);
        }

        if ($product->stock < $quantity) {
            throw new InsufficientStockException(
                $product->id,
                $quantity,
                $product->stock
            );
        }

        if (Auth::check()) {
            $query = $this->getCart()->items()->where('produit_id', $productId);

            if ($taille) {
                $query->where('taille', $taille);
            }

            $item = $query->first();

            if ($item) {
                $item->updateQuantity($quantity);
            }
        } else {
            $itemKey = $productId . '_' . ($taille ?? 'none');
            $cartItems = Session::get(self::SESSION_CART_KEY, []);

            if (isset($cartItems[$itemKey])) {
                $cartItems[$itemKey]['quantite'] = $quantity;
                Session::put(self::SESSION_CART_KEY, $cartItems);
                $this->updateSessionTotal();
            }
        }
    }

    /**
     * Vide complètement le panier
     */
    public function empty(): void
    {
        if (Auth::check()) {
            $this->getCart()->empty();
        } else {
            Session::forget(self::SESSION_CART_KEY);
            Session::forget(self::SESSION_CART_TOTAL);
        }
    }

    /**
     * Récupère le contenu du panier
     */
    public function getCart(): Panier
    {
        if ($this->cartModel) {
            return $this->cartModel;
        }

        if (Auth::check()) {
            $userId = Auth::id();
            $sessionId = Session::getId();

            $this->cartModel = Panier::forUserOrSession($userId, $sessionId);

            return $this->cartModel;
        }

        return new Panier();
    }

    /**
     * Récupère les articles du panier
     */
    public function getItems(): array
    {
        if (Auth::check()) {
            return $this->getCart()->items->map(fn (PanierItem $item) => [
                'id' => $item->produit_id,
                'product_id' => $item->produit_id,
                'quantite' => $item->quantite,
                'prix_unitaire' => (float) $item->prix_unitaire,
                'taille' => $item->taille,
                'nom' => $item->produit?->nom,
                'image' => $item->produit?->image_url,
                'stock' => $item->produit?->stock,
            ])->values()->all();
        }

        $cartItems = Session::get(self::SESSION_CART_KEY, []);

        if (empty($cartItems)) {
            return [];
        }

        return collect($cartItems)->map(function ($item) {
            $product = Produit::find($item['product_id']);

            if (! $product) {
                return null;
            }

            return array_merge($item, [
                'id' => $item['product_id'],
                'prix_unitaire' => $product->prix,
                'image' => $product->image_url,
                'nom' => $product->nom,
                'stock' => $product->stock,
            ]);
        })->filter()->values()->all();
    }

    /**
     * Calcule le total du panier
     */
    public function getTotal(): float
    {
        if (Auth::check()) {
            return (float)$this->getCart()->total;
        }

        return (float)Session::get(self::SESSION_CART_TOTAL, 0);
    }

    /**
     * Compte les articles dans le panier
     */
    public function countItems(): int
    {
        if (Auth::check()) {
            return $this->getCart()->countItems();
        }

        $items = Session::get(self::SESSION_CART_KEY, []);

        return array_reduce($items, function ($carry, $item) {
            return $carry + $item['quantite'];
        }, 0);
    }

    /**
     * Synchronise le panier de session vers la base de données lors de la connexion
     */
    public function syncSessionToDatabase(): void
    {
        $sessionItems = Session::get(self::SESSION_CART_KEY, []);

        if (empty($sessionItems)) {
            return;
        }

        foreach ($sessionItems as $item) {
            try {
                $this->addItemToDatabase(
                    new CartItemData(
                        productId: $item['product_id'],
                        quantite: $item['quantite'],
                        taille: $item['taille'] ?? null
                    ),
                    Produit::find($item['product_id'])
                );
            } catch (\Exception $e) {
                // Ignorer les erreurs (stock insuffisant, produit supprimé, etc.)
                continue;
            }
        }

        Session::forget(self::SESSION_CART_KEY);
        Session::forget(self::SESSION_CART_TOTAL);
    }

    /**
     * Met à jour le total en session
     */
    protected function updateSessionTotal(): void
    {
        $items = Session::get(self::SESSION_CART_KEY, []);

        $total = array_reduce($items, function ($carry, $item) {
            return $carry + ($item['prix_unitaire'] * $item['quantite']);
        }, 0);

        Session::put(self::SESSION_CART_TOTAL, $total);
    }

    /**
     * Marque le panier comme convertis en commande
     */
    public function convertToOrder(): void
    {
        if (Auth::check()) {
            $this->getCart()->convertToOrder();
        } else {
            $this->empty();
        }
    }

    /**
     * Obtient les informations du panier pour l'affichage
     */
    public function getCartInfo(): array
    {
        return [
            'items' => $this->getItems(),
            'total' => $this->getTotal(),
            'count' => $this->countItems(),
            'is_empty' => $this->countItems() === 0,
        ];
    }
}
