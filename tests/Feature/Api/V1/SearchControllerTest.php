<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\Produit;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected array $products;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'nom' => 'Électronique',
            'slug' => 'electronique',
        ]);

        $this->products = [
            Produit::create([
                'nom' => 'iPhone 14 Pro',
                'description' => 'Smartphone Apple dernier modèle',
                'prix' => 999.99,
                'stock' => 5,
                'category_id' => $this->category->id,
            ]),
            Produit::create([
                'nom' => 'Samsung Galaxy S23',
                'description' => 'Smartphone haute performance',
                'prix' => 899.99,
                'stock' => 3,
                'category_id' => $this->category->id,
            ]),
            Produit::create([
                'nom' => 'iPad Air',
                'description' => 'Tablette Apple performante',
                'prix' => 699.99,
                'stock' => 0,
                'category_id' => $this->category->id,
            ]),
        ];
    }

    public function test_search_returns_paginated_results(): void
    {
        $response = $this->getJson('/api/v1/search?q=iPhone');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => ['*' => ['id', 'nom', 'prix', 'stock']],
                    'pagination' => ['current_page', 'total', 'per_page', 'last_page', 'has_more'],
                ],
            ])
            ->assertJsonFragment(['success' => true]);

        $this->assertCount(1, $response->json('data.data'));
        $this->assertEquals('iPhone 14 Pro', $response->json('data.data.0.nom'));
    }

    public function test_search_requires_at_least_two_characters(): void
    {
        $response = $this->getJson('/api/v1/search?q=i');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

    public function test_autocomplete_returns_suggestions(): void
    {
        $response = $this->getJson('/api/v1/search/autocomplete?q=iPhone');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['*' => ['id', 'label', 'value', 'price', 'image']],
            ])
            ->assertJson(['success' => true]);

        $this->assertCount(1, $response->json('data'));
    }

    public function test_advanced_search_applies_filters(): void
    {
        $response = $this->getJson('/api/v1/search/advanced?'.http_build_query([
            'search' => 'iPhone',
            'category_id' => $this->category->id,
            'price_min' => 900,
            'price_max' => 1200,
            'sort_by' => 'price_asc',
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['data', 'pagination', 'filters_applied'],
            ]);

        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_search_by_category_filters_correctly(): void
    {
        $response = $this->getJson("/api/v1/search/category/{$this->category->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => ['*' => ['category_id']],
                    'pagination',
                ],
            ]);

        $this->assertCount(2, $response->json('data.data'));
    }

    public function test_search_excludes_out_of_stock_by_default(): void
    {
        $response = $this->getJson('/api/v1/search?q=iPad');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data.data'));
    }

    public function test_popular_searches_returns_trending_products(): void
    {
        $response = $this->getJson('/api/v1/search/popular');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_count_returns_result_count(): void
    {
        $response = $this->getJson('/api/v1/search/count?q=iPhone');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['count', 'query'],
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'count' => 1,
                    'query' => 'iPhone',
                ],
            ]);
    }

    public function test_search_respects_per_page_parameter(): void
    {
        for ($i = 0; $i < 20; $i++) {
            Produit::create([
                'nom' => "Produit Test $i",
                'description' => 'Test product',
                'prix' => 100,
                'stock' => 1,
                'category_id' => $this->category->id,
            ]);
        }

        $response = $this->getJson('/api/v1/search?q=Produit&per_page=5');

        $response->assertStatus(200);
        $this->assertEquals(5, $response->json('data.pagination.per_page'));
        $this->assertCount(5, $response->json('data.data'));
    }
}
