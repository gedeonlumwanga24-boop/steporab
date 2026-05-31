<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produit;
use App\Models\Category;
use App\Models\Panier;
use App\Models\PanierItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Produit $product;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'nom' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $this->product = Produit::create([
            'nom' => 'Test Product',
            'description' => 'Test Description',
            'prix' => 100.00,
            'stock' => 10,
            'category_id' => $this->category->id,
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * @test
     * GET /api/v1/cart - Récupère le panier vide
     */
    public function get_empty_cart(): void
    {
        $response = $this->getJson('/api/v1/cart');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['items', 'total', 'count', 'is_empty'],
                 ])
                 ->assertJson([
                     'data' => [
                         'total' => 0,
                         'count' => 0,
                         'is_empty' => true,
                     ],
                 ]);
    }

    /**
     * @test
     * POST /api/v1/cart/items - Ajoute un article au panier
     */
    public function add_item_to_cart(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 2,
            'taille' => 'M',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertCount(1, $response['data']['items']);
        $this->assertEquals(200, $response['data']['total']);
        $this->assertEquals(2, $response['data']['count']);
    }

    /**
     * @test
     * POST /api/v1/cart/items - Stockage insuffisant
     */
    public function add_item_insufficient_stock(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 20,
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['success' => false]);
    }

    /**
     * @test
     * POST /api/v1/cart/items - Produit inexistant
     */
    public function add_item_product_not_found(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => 999,
            'quantite' => 1,
        ]);

        $response->assertStatus(404);
    }

    /**
     * @test
     * PATCH /api/v1/cart/items/{id} - Met à jour la quantité
     */
    public function update_item_quantity(): void
    {
        // Ajouter d'abord un article
        $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 1,
        ]);

        // Mettre à jour la quantité
        $response = $this->patchJson("/api/v1/cart/items/{$this->product->id}", [
            'quantite' => 3,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => ['count' => 3],
                 ]);
    }

    /**
     * @test
     * DELETE /api/v1/cart/items/{id} - Supprime un article
     */
    public function remove_item_from_cart(): void
    {
        // Ajouter d'abord un article
        $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 1,
        ]);

        // Supprimer l'article
        $response = $this->deleteJson("/api/v1/cart/items/{$this->product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => ['is_empty' => true],
                 ]);
    }

    /**
     * @test
     * DELETE /api/v1/cart - Vide le panier
     */
    public function clear_cart(): void
    {
        // Ajouter des articles
        $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 2,
        ]);

        // Vider le panier
        $response = $this->deleteJson('/api/v1/cart');

        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'total' => 0,
                         'count' => 0,
                     ],
                 ]);
    }

    /**
     * @test
     * Panier pour utilisateur authentifié
     */
    public function authenticated_user_cart(): void
    {
        Sanctum::actingAs($this->user);

        // Ajouter un article
        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 1,
        ]);

        $response->assertStatus(201);

        // Vérifier que l'article est sauvegardé en DB
        $cart = Panier::where('user_id', $this->user->id)->first();
        $this->assertNotNull($cart);
        $this->assertCount(1, $cart->items);
    }

    /**
     * @test
     * Panier pour utilisateur non authentifié (session)
     */
    public function guest_cart_uses_session(): void
    {
        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 1,
        ]);

        $response->assertStatus(201);

        // Vérifier que le panier est stocké en session
        $this->assertNotNull(session('cart_items'));
    }

    /**
     * @test
     * Ajout de plusieurs articles avec des tailles différentes
     */
    public function add_same_product_different_sizes(): void
    {
        // Ajouter la même article avec taille M
        $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 1,
            'taille' => 'M',
        ]);

        // Ajouter la même article avec taille L
        $response = $this->postJson('/api/v1/cart/items', [
            'product_id' => $this->product->id,
            'quantite' => 1,
            'taille' => 'L',
        ]);

        // Devrait avoir 2 articles distincts
        $this->assertCount(2, $response['data']['items']);
        $this->assertEquals(2, $response['data']['count']);
    }
}
