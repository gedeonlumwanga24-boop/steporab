<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\CartService;
use App\DTOs\CartItemData;
use App\Models\Produit;
use App\Models\Category;
use App\Models\User;
use App\Models\Panier;
use App\Exceptions\InsufficientStockException;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;
    protected Produit $product;
    protected Category $category;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartService = app(CartService::class);

        $this->category = Category::create([
            'nom' => 'Test',
            'slug' => 'test',
        ]);

        $this->product = Produit::create([
            'nom' => 'Test Product',
            'description' => 'Test',
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
     * Ajouter un article au panier en session
     */
    public function add_item_to_session_cart(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2,
            taille: 'M'
        );

        $this->cartService->addItem($cartItem);

        $this->assertNotNull(session('cart_items'));
        $this->assertEquals(2, $this->cartService->countItems());
        $this->assertEquals(200, $this->cartService->getTotal());
    }

    /**
     * @test
     * Ajouter le même article augmente la quantité
     */
    public function add_same_item_increases_quantity(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);
        $this->cartService->addItem($cartItem);

        $this->assertEquals(4, $this->cartService->countItems());
        $this->assertEquals(400, $this->cartService->getTotal());
    }

    /**
     * @test
     * Lancer une exception si le stock est insuffisant
     */
    public function throw_exception_insufficient_stock(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 20
        );

        $this->expectException(InsufficientStockException::class);
        $this->cartService->addItem($cartItem);
    }

    /**
     * @test
     * Lancer une exception si le produit n'existe pas
     */
    public function throw_exception_product_not_found(): void
    {
        $cartItem = new CartItemData(
            productId: 999,
            quantite: 1
        );

        $this->expectException(ProductNotFoundException::class);
        $this->cartService->addItem($cartItem);
    }

    /**
     * @test
     * Supprimer un article du panier
     */
    public function remove_item_from_cart(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);
        $this->cartService->removeItem($this->product->id);

        $this->assertEquals(0, $this->cartService->countItems());
        $this->assertEquals(0, $this->cartService->getTotal());
    }

    /**
     * @test
     * Mettre à jour la quantité d'un article
     */
    public function update_item_quantity(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);
        $this->cartService->updateQuantity($this->product->id, 5);

        $this->assertEquals(5, $this->cartService->countItems());
        $this->assertEquals(500, $this->cartService->getTotal());
    }

    /**
     * @test
     * Mettre à jour la quantité à 0 supprime l'article
     */
    public function update_quantity_to_zero_removes_item(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);
        $this->cartService->updateQuantity($this->product->id, 0);

        $this->assertEquals(0, $this->cartService->countItems());
    }

    /**
     * @test
     * Vider le panier
     */
    public function empty_cart(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);
        $this->cartService->empty();

        $this->assertEquals(0, $this->cartService->countItems());
        $this->assertNull(session('cart_items'));
    }

    /**
     * @test
     * Panier persistant pour utilisateur authentifié
     */
    public function authenticated_user_cart_persists(): void
    {
        Auth::login($this->user);

        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);

        // Vérifier que l'article est sauvegardé en DB
        $cart = Panier::where('user_id', $this->user->id)->first();
        $this->assertNotNull($cart);
        $this->assertCount(1, $cart->items);
    }

    /**
     * @test
     * getCartInfo retourne les infos correctes
     */
    public function get_cart_info_returns_correct_structure(): void
    {
        $cartItem = new CartItemData(
            productId: $this->product->id,
            quantite: 2
        );

        $this->cartService->addItem($cartItem);
        $info = $this->cartService->getCartInfo();

        $this->assertArrayHasKey('items', $info);
        $this->assertArrayHasKey('total', $info);
        $this->assertArrayHasKey('count', $info);
        $this->assertArrayHasKey('is_empty', $info);

        $this->assertCount(1, $info['items']);
        $this->assertEquals(200, $info['total']);
        $this->assertEquals(2, $info['count']);
        $this->assertFalse($info['is_empty']);
    }
}
