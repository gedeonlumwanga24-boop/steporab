<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\V1\CategoryCollection;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends ApiController
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
        private readonly ProductRepositoryInterface $products,
    ) {}

    /**
     * GET /api/v1/categories
     */
    public function index(): JsonResponse
    {
        $categories = $this->categories->all();

        return $this->success(new CategoryCollection($categories));
    }

    /**
     * GET /api/v1/categories/{id}
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->categories->findById($id);

        if (! $category) {
            return $this->notFound('Catégorie introuvable.');
        }

        return $this->success(new CategoryResource($category));
    }

    /**
     * GET /api/v1/categories/{id}/products
     * Retrieve all products belonging to a category with pagination
     */
    public function products(int $id): JsonResponse
    {
        $category = $this->categories->findById($id);

        if (! $category) {
            return $this->notFound('Catégorie introuvable.');
        }

        $products = $this->products->getByCategory($id);

        return $this->success([
            'category' => new CategoryResource($category),
            'products' => $products,
        ]);
    }
}
