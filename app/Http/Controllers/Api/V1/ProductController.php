<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\ProductData;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductCollection;
use App\Http\Resources\Api\V1\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function __construct(
        private readonly ProductRepositoryInterface $products
    ) {}

    /**
     * GET /api/v1/products
     * List products with optional filters: ?search=&category_id=&sort=&per_page=
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'category_id', 'sort']);
        $perPage = (int) $request->get('per_page', 12);

        $products = $this->products->paginate($perPage, $filters);

        return $this->success(new ProductCollection($products));
    }

    /**
     * GET /api/v1/products/{id}
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->products->findById($id);

        if (! $product) {
            return $this->notFound('Produit introuvable.');
        }

        return $this->success(new ProductResource($product));
    }

    /**
     * POST /api/v1/products
     * Admin / Manager only — see StoreProductRequest::authorize()
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = ProductData::fromRequest($request->validated());

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('produits', 'public');
            $data = new ProductData(
                nom: $data->nom,
                prix: $data->prix,
                stock: $data->stock,
                description: $data->description,
                image: basename($path),
                galerie: $data->galerie,
                categoryId: $data->categoryId,
            );
        }

        $product = $this->products->create($data);

        return $this->created(new ProductResource($product));
    }

    /**
     * PATCH /api/v1/products/{id}
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->products->findById($id);

        if (! $product) {
            return $this->notFound('Produit introuvable.');
        }

        $data = ProductData::fromRequest(array_merge(
            $product->toArray(),
            $request->validated()
        ));

        $updated = $this->products->update($product, $data);

        return $this->success(new ProductResource($updated), 'Produit mis à jour.');
    }

    /**
     * DELETE /api/v1/products/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $product = $this->products->findById($id);

        if (! $product) {
            return $this->notFound('Produit introuvable.');
        }

        $this->authorize('delete', $product);

        $this->products->delete($product);

        return $this->noContent();
    }
}
