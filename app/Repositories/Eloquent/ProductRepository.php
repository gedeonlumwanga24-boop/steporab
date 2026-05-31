<?php

namespace App\Repositories\Eloquent;

use App\DTOs\ProductData;
use App\Models\Produit;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator
    {
        $query = Produit::with('category');

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nom', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['sort'])) {
            match ($filters['sort']) {
                'price_asc'  => $query->orderBy('prix', 'asc'),
                'price_desc' => $query->orderBy('prix', 'desc'),
                'latest'     => $query->latest(),
                default      => $query->latest(),
            };
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Produit
    {
        return Produit::with('category')->find($id);
    }

    public function findBySlug(string $slug): ?Produit
    {
        // Searching by slug (nom formatted) — add a `slug` column if needed
        return Produit::with('category')->where('nom', $slug)->first();
    }

    public function search(string $query): Collection
    {
        return Produit::with('category')
            ->where('nom', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(20)
            ->get();
    }

    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        return Produit::with('category')
            ->where('category_id', $categoryId)
            ->latest()
            ->paginate($perPage);
    }

    public function getLatest(int $limit = 8): Collection
    {
        return Produit::with('category')->latest()->limit($limit)->get();
    }

    public function create(ProductData $data): Produit
    {
        return Produit::create([
            'nom'         => $data->nom,
            'prix'        => $data->prix,
            'stock'       => $data->stock,
            'description' => $data->description,
            'image'       => $data->image,
            'galerie'     => $data->galerie,
            'category_id' => $data->categoryId,
        ]);
    }

    public function update(Produit $product, ProductData $data): Produit
    {
        $product->update([
            'nom'         => $data->nom,
            'prix'        => $data->prix,
            'stock'       => $data->stock,
            'description' => $data->description,
            'image'       => $data->image ?? $product->image,
            'galerie'     => $data->galerie ?? $product->galerie,
            'category_id' => $data->categoryId,
        ]);

        return $product->fresh();
    }

    public function delete(Produit $product): bool
    {
        // Optionally remove image from storage
        if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete('produits/' . basename($product->image));
        }

        return (bool) $product->delete();
    }

    public function decrementStock(Produit $product, int $quantity): void
    {
        DB::table('produits')
            ->where('id', $product->id)
            ->decrement('stock', $quantity);
    }
}
