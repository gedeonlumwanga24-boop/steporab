<?php

namespace App\Repositories\Contracts;

use App\DTOs\ProductData;
use App\Models\Produit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator;

    public function findById(int $id): ?Produit;

    public function findBySlug(string $slug): ?Produit;

    public function search(string $query): Collection;

    public function getByCategory(int $categoryId, int $perPage = 12): LengthAwarePaginator;

    public function getLatest(int $limit = 8): Collection;

    public function create(ProductData $data): Produit;

    public function update(Produit $product, ProductData $data): Produit;

    public function delete(Produit $product): bool;

    public function decrementStock(Produit $product, int $quantity): void;
}
