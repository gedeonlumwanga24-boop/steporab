<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;

class ProductService
{
    /**
     * Récupérer tous les produits avec pagination
     */
    public function getAllProducts(int $perPage = 12): Paginator
    {
        return Product::paginate($perPage);
    }

    /**
     * Récupérer les produits par catégorie
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 12): Paginator
    {
        return Product::where('category_id', $categoryId)->paginate($perPage);
    }

    /**
     * Rechercher des produits
     */
    public function searchProducts(string $query): Collection
    {
        return Product::where('nom', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();
    }

    /**
     * Récupérer un produit par ID
     */
    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Créer un nouveau produit
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduct(int $id, array $data): bool
    {
        $product = Product::find($id);
        if (!$product) return false;
        return $product->update($data);
    }

    /**
     * Supprimer un produit
     */
    public function deleteProduct(int $id): bool
    {
        $product = Product::find($id);
        if (!$product) return false;
        return $product->delete();
    }

    /**
     * Obtenir les produits populaires
     */
    public function getPopularProducts(int $limit = 8): Collection
    {
        return Product::orderBy('created_at', 'desc')->limit($limit)->get();
    }
}
