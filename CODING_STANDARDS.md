# 🎯 Guide des Bonnes Pratiques - Stepora

## 📑 Table des matières

1. [Architecture](#architecture)
2. [Conventions de Codage](#conventions-de-codage)
3. [Structure des Modèles](#structure-des-modèles)
4. [Services et Logique Métier](#services-et-logique-métier)
5. [Validation des Données](#validation-des-données)
6. [Gestion des Erreurs](#gestion-des-erreurs)
7. [Patterns Recommandés](#patterns-recommandés)
8. [Performance](#performance)
9. [Sécurité](#sécurité)
10. [Tests](#tests)

---

## Architecture

### Pattern MVC + Services

```
Controller → Request → Service → Repository/Model → Database
    ↑
    └─ Response/Resource
```

### Flux de Traitement d'une Requête

```
1. Route → Controller
2. Controller valide avec FormRequest
3. Controller appelle le Service
4. Service utilise Repository/Model
5. Service retourne les données
6. Controller retourne une Response/Resource
```

### Exemple d'Implémentation

```php
// Route
Route::post('/produits', [ProductController::class, 'store'])->name('produits.store');

// Controller
public function store(StoreProductRequest $request)
{
    $product = $this->productService->createProduct($request->validated());
    return redirect()->route('produits.show', $product);
}

// Service
public function createProduct(array $data): Product
{
    return Product::create($data);
}
```

---

## Conventions de Codage

### Noms des Classes et Fichiers

| Type           | Convention                  | Exemple                        |
| -------------- | --------------------------- | ------------------------------ |
| **Modèle**     | PascalCase Singulier        | `Product.php`                  |
| **Controller** | PascalCase + "Controller"   | `ProductController.php`        |
| **Service**    | PascalCase + "Service"      | `ProductService.php`           |
| **Request**    | Action + Modèle + "Request" | `StoreProductRequest.php`      |
| **Resource**   | Modèle + "Resource"         | `ProductResource.php`          |
| **Repository** | Modèle + "Repository"       | `ProductRepository.php`        |
| **Trait**      | Descriptif + "Trait"        | `HasTimestampsTrait.php`       |
| **Event**      | Action + Modèle + "Event"   | `ProductCreatedEvent.php`      |
| **Listener**   | Action + "Listener"         | `SendEmailListener.php`        |
| **Enum**       | Descriptif                  | `OrderStatus.php`              |
| **Exception**  | Descriptif + "Exception"    | `ProductNotFoundException.php` |

### Noms des Méthodes

```php
// Récupérer des données
public function get...(): ...
public function fetch...(): ...
public function retrieve...(): ...

// Créer des données
public function create...(...): Model
public function store...(...): Model

// Mettre à jour
public function update...(...): bool
public function modify...(...): bool

// Supprimer
public function delete...(...): bool
public function remove...(...): bool

// Vérifier
public function has...(): bool
public function exists...(): bool
public function is...(): bool

// Compter
public function count...(): int
public function total...(): int
```

---

## Structure des Modèles

### Template de Modèle

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasTimestamps;

class Product extends Model
{
    use HasFactory, HasTimestamps;

    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'stock',
        'category_id',
    ];

    protected $casts = [
        'prix' => 'float',
        'stock' => 'integer',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'commande_produit')
                    ->withPivot('quantity', 'price_unit');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Accessors & Mutators
    public function getFormattedPriceAttribute()
    {
        return number_format($this->prix, 2, ',', ' ') . ' €';
    }

    // Custom Methods
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function decreaseStock(int $quantity): void
    {
        $this->update(['stock' => $this->stock - $quantity]);
    }
}
```

### Bonnes Pratiques pour les Modèles

- **Fillable**: Toujours définir les attributs mass-assignables
- **Casts**: Utiliser les casts pour les types (int, float, boolean)
- **Relationships**: Bien définir les relations avec les autres modèles
- **Scopes**: Créer des scopes pour les requêtes communes
- **Custom Methods**: Ajouter des méthodes métier au modèle
- **Pas de Logique Métier Complexe**: Déléguer aux Services

---

## Services et Logique Métier

### Structure d'un Service

```php
<?php

namespace App\Services;

use App\Models\Product;
use App\Exceptions\ProductNotFoundException;

class ProductService
{
    /**
     * Créer un produit
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Mettre à jour un produit
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::find($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        $product->update($data);
        return $product;
    }

    /**
     * Récupérer un produit
     */
    public function getProduct(int $id): Product
    {
        $product = Product::find($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return $product;
    }

    /**
     * Acheter un produit (logique métier complexe)
     */
    public function purchaseProduct(int $productId, int $quantity): Order
    {
        $product = $this->getProduct($productId);

        if ($product->stock < $quantity) {
            throw new InsufficientStockException($productId, $quantity, $product->stock);
        }

        $product->decreaseStock($quantity);

        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $product->prix * $quantity,
            'status' => 'pending',
        ]);

        $order->products()->attach($product, [
            'quantity' => $quantity,
            'price_unit' => $product->prix,
        ]);

        return $order;
    }
}
```

### Règles des Services

- ✅ Contenir la logique métier complexe
- ✅ Utiliser les modèles et repositories
- ✅ Gérer les transactions (DB::transaction)
- ✅ Lever des exceptions personnalisées
- ✅ Être testable (dependency injection)
- ❌ Ne pas contenir de logique HTTP
- ❌ Ne pas accéder directement à $_GET/$\_POST
- ❌ Ne pas appeler d'autres services sans raison

---

## Validation des Données

### Utiliser les Form Requests

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255|unique:produits',
            'description' => 'nullable|string|max:1000',
            'prix' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'nom.unique' => 'Ce produit existe déjà.',
            'prix.min' => 'Le prix doit être positif.',
        ];
    }
}
```

### Usage dans le Controller

```php
public function store(StoreProductRequest $request)
{
    // Les données sont déjà validées
    $product = $this->productService->createProduct($request->validated());
    return redirect()->route('produits.show', $product);
}
```

---

## Gestion des Erreurs

### Exceptions Personnalisées

```php
<?php

namespace App\Exceptions;

use Exception;

class ProductNotFoundException extends Exception
{
    public function __construct(int $productId)
    {
        parent::__construct("Produit {$productId} non trouvé.");
    }
}
```

### Gestion dans le Service

```php
public function getProduct(int $id): Product
{
    $product = Product::find($id);

    if (!$product) {
        throw new ProductNotFoundException($id);
    }

    return $product;
}
```

### Gestion dans le Controller

```php
public function show(Product $product)
{
    try {
        $product = $this->productService->getProduct($product->id);
        return view('produits.show', compact('product'));
    } catch (ProductNotFoundException $e) {
        return redirect()->route('produits.index')
                        ->with('error', $e->getMessage());
    }
}
```

---

## Patterns Recommandés

### 1. Repository Pattern (optionnel pour les projets simples)

```php
interface ProductRepositoryInterface
{
    public function getAll();
    public function find(int $id);
    public function create(array $data);
}

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::all();
    }
}
```

### 2. Observer Pattern pour les Events

```php
// app/Observers/ProductObserver.php
class ProductObserver
{
    public function created(Product $product)
    {
        event(new ProductCreatedEvent($product));
    }
}

// app/Providers/AppServiceProvider.php
Product::observe(ProductObserver::class);
```

### 3. Dependency Injection

```php
class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}

    public function show(Product $product)
    {
        $product = $this->productService->getProduct($product->id);
    }
}
```

---

## Performance

### Eager Loading

```php
// ❌ N+1 Query Problem
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->nom; // Requête supplémentaire
}

// ✅ Eager Loading
$products = Product::with('category')->get();
```

### Pagination

```php
// ❌ Pas de pagination
$products = Product::all();

// ✅ Avec pagination
$products = Product::paginate(12);
```

### Caching

```php
// ❌ Requête à chaque fois
public function getCategories()
{
    return Category::all();
}

// ✅ Avec cache
public function getCategories()
{
    return Cache::remember('categories', 3600, function () {
        return Category::all();
    });
}
```

---

## Sécurité

### CSRF Protection

Toujours inclure `@csrf` dans les formulaires:

```blade
<form method="POST" action="/produits">
    @csrf
    <!-- Contenu du formulaire -->
</form>
```

### Authorization

```php
// Dans le FormRequest
public function authorize(): bool
{
    return auth()->user()?->isAdmin() ?? false;
}

// Dans le Controller (avec policy)
$this->authorize('update', $product);
```

### Mass Assignment Protection

```php
// ✅ Toujours définir $fillable
protected $fillable = ['nom', 'prix', 'stock'];
```

### Validation

```php
// ✅ Valider TOUJOURS les données utilisateur
$validated = $request->validate([
    'email' => 'required|email',
    'nom' => 'required|string|max:255',
]);
```

---

## Tests

### Structure des Tests

```php
// tests/Feature/ProductControllerTest.php
class ProductControllerTest extends TestCase
{
    public function test_can_list_products()
    {
        $products = Product::factory(3)->create();

        $response = $this->get('/produits');

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_can_create_product()
    {
        $data = [
            'nom' => 'Product Test',
            'prix' => 29.99,
            'stock' => 10,
            'category_id' => Category::factory()->create()->id,
        ];

        $response = $this->post('/produits', $data);

        $this->assertDatabaseHas('produits', ['nom' => 'Product Test']);
    }
}
```

### Commandes Utiles

```bash
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage

# Tester un fichier spécifique
php artisan test tests/Feature/ProductControllerTest.php

# Mode watch (relancer automatiquement)
php artisan test --watch
```

---

## ✅ Checklist de Qualité

- [ ] Code conforme aux conventions PSR-12
- [ ] Type hints partout où possible
- [ ] Documentation des méthodes publiques
- [ ] Tests unitaires pour les Services
- [ ] Tests fonctionnels pour les Controllers
- [ ] Pas de requêtes N+1
- [ ] Cache implémenté où approprié
- [ ] Gestion des erreurs complète
- [ ] Validation des données robuste
- [ ] Code DRY (Don't Repeat Yourself)

---

**Dernière mise à jour**: 2 mai 2026
