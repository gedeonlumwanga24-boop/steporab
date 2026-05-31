<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SearchService;
use App\Models\Produit;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SearchService $searchService;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = app(SearchService::class);

        $this->category = Category::create([
            'nom' => 'Test Category',
            'slug' => 'test-category',
        ]);

        // Créer des produits de test
        Produit::create([
            'nom' => 'MacBook Pro',
            'description' => 'Ordinateur portable professionnel',
            'prix' => 1299.99,
            'stock' => 2,
            'category_id' => $this->category->id,
        ]);

        Produit::create([
            'nom' => 'MacBook Air',
            'description' => 'Ordinateur portable léger',
            'prix' => 999.99,
            'stock' => 5,
            'category_id' => $this->category->id,
        ]);

        Produit::create([
            'nom' => 'iPad Pro',
            'description' => 'Tablette Apple',
            'prix' => 799.99,
            'stock' => 0,
            'category_id' => $this->category->id,
        ]);
    }

    /**
     * @test
     * searchProducts trouve les produits par mot-clé
     */
    public function search_products_finds_items_by_keyword(): void
    {
        $results = $this->searchService->searchProducts('MacBook');

        $this->assertCount(2, $results->items());
    }

    /**
     * @test
     * autocomplete retourne les suggestions formatées
     */
    public function autocomplete_returns_formatted_suggestions(): void
    {
        $suggestions = $this->searchService->autocomplete('Mac', 10);

        $this->assertCount(2, $suggestions);
        $this->assertArrayHasKey('id', $suggestions[0]);
        $this->assertArrayHasKey('label', $suggestions[0]);
        $this->assertArrayHasKey('value', $suggestions[0]);
        $this->assertArrayHasKey('price', $suggestions[0]);
    }

    /**
     * @test
     * searchByCategory filtre par catégorie
     */
    public function search_by_category_filters_correctly(): void
    {
        $results = $this->searchService->searchByCategory($this->category->id);

        // Devrait retourner 2 produits (MacBook Pro et MacBook Air, iPad Pro en rupture)
        $this->assertCount(2, $results->items());
    }

    /**
     * @test
     * advancedSearch applique tous les filtres
     */
    public function advanced_search_applies_all_filters(): void
    {
        $filters = [
            'search' => 'MacBook',
            'category_id' => $this->category->id,
            'price_min' => 1000,
            'price_max' => 1500,
        ];

        $results = $this->searchService->advancedSearch($filters);

        // Seul MacBook Pro correspond aux critères
        $this->assertCount(1, $results->items());
        $this->assertEquals('MacBook Pro', $results->items()[0]['nom']);
    }

    /**
     * @test
     * advancedSearch peut inclure les produits en rupture
     */
    public function advanced_search_includes_out_of_stock_if_requested(): void
    {
        $filters = [
            'search' => 'iPad',
            'include_out_of_stock' => true,
        ];

        $results = $this->searchService->advancedSearch($filters);

        $this->assertCount(1, $results->items());
        $this->assertEquals('iPad Pro', $results->items()[0]['nom']);
    }

    /**
     * @test
     * countResults compte les résultats
     */
    public function count_results_returns_correct_count(): void
    {
        $count = $this->searchService->countResults('MacBook');

        $this->assertEquals(2, $count);
    }

    /**
     * @test
     * getPopularSearches retourne les recherches populaires
     */
    public function get_popular_searches_returns_suggestions(): void
    {
        $popular = $this->searchService->getPopularSearches();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $popular);
        $this->assertGreaterThan(0, $popular->count());
    }

    /**
     * @test
     * La recherche n'inclut pas les produits en rupture de stock par défaut
     */
    public function search_excludes_out_of_stock_by_default(): void
    {
        $results = $this->searchService->searchProducts('iPad');

        // iPad Pro est en rupture et ne doit pas apparaître
        $this->assertCount(0, $results->items());
    }
}
