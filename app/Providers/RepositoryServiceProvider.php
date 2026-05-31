<?php

namespace App\Providers;

use App\Repositories\Cache\CachedCategoryRepository;
use App\Repositories\Cache\CachedProductRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * The Cache decorators wrap the Eloquent repositories transparently.
     * Controllers and Services only depend on the Interfaces.
     */
    public function register(): void
    {
        // Product: CachedRepository wraps EloquentRepository
        $this->app->bind(ProductRepositoryInterface::class, function ($app) {
            return new CachedProductRepository(
                new ProductRepository()
            );
        });

        // Category: CachedRepository wraps EloquentRepository
        $this->app->bind(CategoryRepositoryInterface::class, function ($app) {
            return new CachedCategoryRepository(
                new CategoryRepository()
            );
        });

        // Order: No cache needed (orders must always be fresh)
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
