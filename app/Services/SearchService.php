<?php

namespace App\Services;

use App\Models\Produit;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchService
{
    public function searchProducts(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return Produit::query()
            ->where('stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderByRaw('CASE WHEN nom LIKE ? THEN 1 WHEN description LIKE ? THEN 2 ELSE 3 END', ["%{$query}%", "%{$query}%"])
            ->latest()
            ->paginate($perPage);
    }

    public function autocomplete(string $query, int $limit = 10): array
    {
        return Produit::where('nom', 'like', "%{$query}%")
            ->where('stock', '>', 0)
            ->select('id', 'nom', 'prix', 'image')
            ->limit($limit)
            ->get()
            ->map(fn ($product) => [
                'id' => $product->id,
                'label' => $product->nom,
                'value' => $product->nom,
                'price' => $product->prix,
                'image' => $product->image_url,
            ])
            ->values()
            ->all();
    }

    public function searchByCategory(int $categoryId, ?string $query = null, int $perPage = 12): LengthAwarePaginator
    {
        $builder = Produit::where('category_id', $categoryId)
            ->where('stock', '>', 0);

        if ($query) {
            $builder->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        return $builder->latest()->paginate($perPage);
    }

    public function advancedSearch(array $filters): LengthAwarePaginator
    {
        $query = Produit::query();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nom', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['price_min'])) {
            $query->where('prix', '>=', (float) $filters['price_min']);
        }

        if (! empty($filters['price_max'])) {
            $query->where('prix', '<=', (float) $filters['price_max']);
        }

        if (empty($filters['include_out_of_stock'])) {
            $query->where('stock', '>', 0);
        }

        $sortBy = $filters['sort_by'] ?? 'latest';
        $this->applySorting($query, $sortBy);

        return $query->paginate($filters['per_page'] ?? 12);
    }

    private function applySorting($query, string $sortBy): void
    {
        match ($sortBy) {
            'price_asc' => $query->orderBy('prix', 'asc'),
            'price_desc' => $query->orderBy('prix', 'desc'),
            'popular' => $query->orderBy('stock', 'desc'),
            'name_asc' => $query->orderBy('nom', 'asc'),
            'name_desc' => $query->orderBy('nom', 'desc'),
            default => $query->latest(),
        };
    }

    public function getPopularSearches(): Collection
    {
        return Produit::query()
            ->where('stock', '>', 0)
            ->select('nom')
            ->distinct()
            ->orderBy('nom')
            ->limit(8)
            ->pluck('nom');
    }

    public function countResults(string $query): int
    {
        return Produit::query()
            ->where('stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->count();
    }
}
