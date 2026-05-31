<?php

namespace App\Repositories\Cache;

use App\DTOs\ProductData;
use App\Models\Produit;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CachedProductRepository implements ProductRepositoryInterface
{
    private const TTL = 3600; // 1 hour

    public function __construct(
        private readonly ProductRepositoryInterface $repository
    ) {}

    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator
    {
        $cacheKey = 'products.paginate.' . md5(json_encode(compact('perPage', 'filters')));

        return Cache::remember($cacheKey, self::TTL, fn () =>
            $this->repository->paginate($perPage, $filters)
        );
    }

    public function findById(int $id): ?Produit
    {
        return Cache::remember("products.{$id}", self::TTL, fn () =>
            $this->repository->findById($id)
        );
    }

    public function findBySlug(string $slug): ?Produit
    {
        return Cache::remember("products.slug.{$slug}", self::TTL, fn () =>
            $this->repository->findBySlug($slug)
        );
    }

    public function search(string $query): Collection
    {
        // Searches are short-lived (2 min)
        return Cache::remember("products.search.{$query}", 120, fn () =>
            $this->repository->search($query)
        );
    }

    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator
    {
        $cacheKey = "products.category.{$categoryId}.page.{$perPage}";

        return Cache::remember($cacheKey, self::TTL, fn () =>
            $this->repository->getByCategory($categoryId, $perPage)
        );
    }

    public function getLatest(int $limit = 8): Collection
    {
        return Cache::remember("products.latest.{$limit}", self::TTL, fn () =>
            $this->repository->getLatest($limit)
        );
    }

    // Write operations: delegate directly and flush relevant cache
    public function create(ProductData $data): Produit
    {
        $product = $this->repository->create($data);
        $this->flush();

        return $product;
    }

    public function update(Produit $product, ProductData $data): Produit
    {
        $updated = $this->repository->update($product, $data);
        $this->flush($product->id);

        return $updated;
    }

    public function delete(Produit $product): bool
    {
        $result = $this->repository->delete($product);
        $this->flush($product->id);

        return $result;
    }

    public function decrementStock(Produit $product, int $quantity): void
    {
        $this->repository->decrementStock($product, $quantity);
        Cache::forget("products.{$product->id}");
    }

    private function flush(?int $id = null): void
    {
        Cache::forget('products.paginate.*');
        Cache::forget('products.latest.*');

        if ($id) {
            Cache::forget("products.{$id}");
        }
    }
}
