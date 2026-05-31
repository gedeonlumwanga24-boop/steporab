<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes — Stepora E-Commerce
|--------------------------------------------------------------------------
| Prefix  : /api/v1  (set in bootstrap/app.php)
| Auth    : Laravel Sanctum (stateful for SPA, token for mobile)
| Throttle: 60 req/min for public, 30 req/min for auth
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ------------------------------------------------------------------
    // AUTH — rate limited to 10 attempts/min to prevent brute-force
    // ------------------------------------------------------------------
    Route::prefix('auth')->name('auth.')->middleware('throttle:10,1')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login',    [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout',        [AuthController::class, 'logout'])->name('logout');
            Route::get('/me',             [AuthController::class, 'me'])->name('me');
            Route::patch('/profile',      [AuthController::class, 'updateProfile'])->name('profile.update');
        });
    });

    // ------------------------------------------------------------------
    // PUBLIC ROUTES (no auth required)
    // ------------------------------------------------------------------

    // Products catalogue
    Route::get('/products',     [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Categories
    Route::get('/categories',              [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{id}',         [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{id}/products', [CategoryController::class, 'products'])->name('categories.products');

    // Search — Dynamic search with autocomplete, filters, and pagination
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/',           [SearchController::class, 'index'])->name('index');
        Route::get('/products',   [SearchController::class, 'products'])->name('products');
        Route::get('/autocomplete', [SearchController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/advanced',   [SearchController::class, 'advanced'])->name('advanced');
        Route::get('/category/{id}', [SearchController::class, 'byCategory'])->name('by-category');
        Route::get('/popular',    [SearchController::class, 'popularSearches'])->name('popular');
        Route::get('/count',      [SearchController::class, 'count'])->name('count');
    });

    // ------------------------------------------------------------------
    // CART — works for guests (session) and authenticated users
    // ------------------------------------------------------------------
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/',              [CartController::class, 'index'])->name('index');
        Route::delete('/',           [CartController::class, 'clear'])->name('clear');
        Route::post('/items',        [CartController::class, 'store'])->name('items.store');
        Route::patch('/items/{item}', [CartController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [CartController::class, 'destroy'])->name('items.destroy');
    });

    // ------------------------------------------------------------------
    // AUTHENTICATED ROUTES (Sanctum token or SPA session)
    // ------------------------------------------------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Customer orders
        Route::get('/orders',      [OrderController::class, 'index'])->name('orders.index');
        Route::post('/orders',     [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

        // Admin/Manager: update order status
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])
            ->name('orders.update-status')
            ->middleware('throttle:30,1');

        // Admin/Manager: product management
        Route::middleware('throttle:30,1')->group(function () {
            Route::post('/products',       [ProductController::class, 'store'])->name('products.store');
            Route::patch('/products/{id}', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        });
    });
});
