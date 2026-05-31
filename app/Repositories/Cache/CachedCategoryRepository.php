<?php

namespace App\Repositories\Cache;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CachedCategoryRepository implements CategoryRepositoryInterface
{
    private const TTL = 7200; // 2 hours (categories change rarely)

    public function __construct(
        private readonly CategoryRepositoryInterface $repository
    ) {}

    public function all(): Collection
    {
        return Cache::remember('categories.all', self::TTL, fn () =>
            $this->repository->all()
        );
    }

    public function findById(int $id): ?Category
    {
        return Cache::remember("categories.{$id}", self::TTL, fn () =>
            $this->repository->findById($id)
        );
    }

    public function findBySlug(string $slug): ?Category
    {
        return Cache::remember("categories.slug.{$slug}", self::TTL, fn () =>
            $this->repository->findBySlug($slug)
        );
    }

    public function getNavigation(): Collection
    {
        return Cache::remember('categories.navigation', self::TTL, fn () =>
            $this->repository->getNavigation()
        );
    }

    public function create(array $data): Category
    {
        $category = $this->repository->create($data);
        $this->flush();

        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $updated = $this->repository->update($category, $data);
        $this->flush($category->id);

        return $updated;
    }

    public function delete(Category $category): bool
    {
        $result = $this->repository->delete($category);
        $this->flush($category->id);

        return $result;
    }

    private function flush(?int $id = null): void
    {
        Cache::forget('categories.all');
        Cache::forget('categories.navigation');

        if ($id) {
            Cache::forget("categories.{$id}");
        }
    }
}
