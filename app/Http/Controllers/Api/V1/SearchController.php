<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Services\SearchService;
use App\Http\Resources\Api\V1\ProductCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends ApiController
{
    public function __construct(
        private readonly SearchService $searchService
    ) {}

    /**
     * Recherche générale avec pagination
     * GET /api/v1/search?q=...&per_page=12
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $query = $validated['q'];
        $perPage = $validated['per_page'] ?? 12;

        $results = $this->searchService->searchProducts($query, $perPage);

        return $this->success([
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'last_page' => $results->lastPage(),
                'has_more' => $results->hasMorePages(),
            ],
        ]);
    }

    /**
     * Autocomplete / Suggestions rapides
     * GET /api/v1/search/autocomplete?q=...&limit=10
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:1|max:50',
            'limit' => 'nullable|integer|min:1|max:20',
        ]);

        $suggestions = $this->searchService->autocomplete(
            $validated['q'],
            $validated['limit'] ?? 10
        );

        return $this->success($suggestions);
    }

    /**
     * Recherche avancée avec filtres
     * GET /api/v1/search/advanced?search=...&category_id=...&price_min=...&price_max=...
     */
    public function advanced(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'sort_by' => 'nullable|in:latest,price_asc,price_desc,popular,name_asc,name_desc',
            'per_page' => 'nullable|integer|min:1|max:100',
            'include_out_of_stock' => 'nullable|boolean',
        ]);

        $results = $this->searchService->advancedSearch($validated);

        return $this->success([
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'last_page' => $results->lastPage(),
            ],
            'filters_applied' => $validated,
        ]);
    }

    /**
     * Recherche par catégorie
     * GET /api/v1/search/category/{id}?q=...&per_page=12
     */
    public function byCategory(Request $request, int $categoryId): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $results = $this->searchService->searchByCategory(
            $categoryId,
            $validated['q'] ?? null,
            $validated['per_page'] ?? 12
        );

        return $this->success([
            'data' => $results->items(),
            'pagination' => [
                'current_page' => $results->currentPage(),
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'last_page' => $results->lastPage(),
            ],
        ]);
    }

    /**
     * Suggestions populaires
     * GET /api/v1/search/popular
     */
    public function popularSearches(): JsonResponse
    {
        $suggestions = $this->searchService->getPopularSearches();

        return $this->success($suggestions);
    }

    /**
     * Compte le nombre de résultats
     * GET /api/v1/search/count?q=...
     */
    public function count(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:255',
        ]);

        $count = $this->searchService->countResults($validated['q']);

        return $this->success([
            'count' => $count,
            'query' => $validated['q'],
        ]);
    }

    /**
     * Recherche produits (ancienne méthode - compatibilité)
     * GET /api/v1/search/products?q=...
     */
    public function products(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return $this->error('La recherche doit contenir au moins 2 caractères.', 422);
        }

        $results = $this->searchService->searchProducts($query, 20);

        return $this->success([
            'data' => $results->items(),
        ]);
    }
}
