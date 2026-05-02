# 🚀 Guide de Développement - Stepora

## 📋 Table des matières
1. [Installation et Configuration](#installation-et-configuration)
2. [Démarrage du Projet](#démarrage-du-projet)
3. [Commandes Laravel Essentielles](#commandes-laravel-essentielles)
4. [Créer une Nouvelle Fonctionnalité](#créer-une-nouvelle-fonctionnalité)
5. [Workflow de Développement](#workflow-de-développement)
6. [Dépannage](#dépannage)
7. [Ressources Utiles](#ressources-utiles)

---

## Installation et Configuration

### 1️⃣ Première Installation

```bash
# Cloner le projet
git clone <repository>
cd stepora

# Installer les dépendances PHP
composer install

# Installer les dépendances JavaScript
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé de l'application
php artisan key:generate

# Configurer la base de données dans .env
# DB_DATABASE=stepora
# DB_USERNAME=root
# DB_PASSWORD=

# Lancer les migrations
php artisan migrate

# Seeder la base de données (optionnel)
php artisan db:seed
```

### 2️⃣ Configuration de l'Environnement

Éditer le fichier `.env`:

```env
APP_NAME=Stepora
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stepora
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@stepora.local

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## Démarrage du Projet

### Mode Développement

```bash
# Terminal 1: Serveur PHP
php artisan serve

# Terminal 2: Compiler les assets
npm run dev
```

Accéder à: `http://localhost:8000`

### Mode Production

```bash
# Compiler les assets
npm run build

# Mettre en cache les config
php artisan config:cache
php artisan route:cache
```

---

## Commandes Laravel Essentielles

### 🗄️ Base de Données

```bash
# Lancer les migrations
php artisan migrate

# Faire un rollback (annuler dernière migration)
php artisan migrate:rollback

# Réinitialiser la BD
php artisan migrate:refresh

# Seeder la base de données
php artisan db:seed

# Seeder une classe spécifique
php artisan db:seed --class=ProduitSeeder
```

### 🔧 Générer des Fichiers

```bash
# Créer un modèle
php artisan make:model Product

# Créer un modèle avec migration
php artisan make:model Product -m

# Créer un contrôleur
php artisan make:controller ProductController

# Créer un contrôleur avec ressources
php artisan make:controller ProductController --resource

# Créer un FormRequest
php artisan make:request StoreProductRequest

# Créer une Resource (API)
php artisan make:resource ProductResource

# Créer un Service (manuel, voir structure)
# Créer app/Services/ProductService.php

# Créer une Migration
php artisan make:migration create_products_table

# Créer un Seeder
php artisan make:seeder ProductSeeder

# Créer un Event
php artisan make:event ProductCreatedEvent

# Créer un Listener
php artisan make:listener SendProductEmailListener

# Créer un Job
php artisan make:job ProcessProductJob
```

### 🧪 Tests

```bash
# Lancer tous les tests
php artisan test

# Tests avec couverture
php artisan test --coverage

# Tests en mode watch
php artisan test --watch

# Tester un fichier spécifique
php artisan test tests/Feature/ProductTest.php
```

### 🔐 Cache et Configuration

```bash
# Vider tout le cache
php artisan cache:clear

# Vider le cache des configurations
php artisan config:clear

# Vider le cache des routes
php artisan route:clear

# Vider le cache des views
php artisan view:clear

# Mettre en cache les configurations
php artisan config:cache

# Mettre en cache les routes
php artisan route:cache
```

### 🛠️ Autres Commandes Utiles

```bash
# Liste de toutes les routes
php artisan route:list

# Mode maintenance (OFF: php artisan up)
php artisan down

# Vérifier la santé de l'application
php artisan health

# Afficher les infos de l'app
php artisan about
```

---

## Créer une Nouvelle Fonctionnalité

### Exemple: Ajouter un Système de Reviews (Avis)

#### 1️⃣ Créer le Modèle et la Migration

```bash
php artisan make:model Review -m
```

Éditer la migration:
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('produits')->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->integer('rating'); // 1-5
    $table->text('comment');
    $table->timestamps();
});
```

#### 2️⃣ Définir le Modèle

```php
// app/Models/Review.php
class Review extends Model
{
    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

#### 3️⃣ Créer un Service

```bash
# app/Services/ReviewService.php
```

```php
class ReviewService
{
    public function createReview(int $productId, array $data): Review
    {
        return Review::create([
            'product_id' => $productId,
            'user_id' => auth()->id(),
            ...$data
        ]);
    }
}
```

#### 4️⃣ Créer le Contrôleur

```bash
php artisan make:controller ReviewController
```

```php
class ReviewController extends Controller
{
    public function __construct(private ReviewService $reviewService) {}

    public function store(StoreReviewRequest $request, Product $product)
    {
        $this->reviewService->createReview($product->id, $request->validated());
        return back()->with('success', 'Avis ajouté!');
    }
}
```

#### 5️⃣ Créer le FormRequest

```bash
php artisan make:request StoreReviewRequest
```

#### 6️⃣ Ajouter les Routes

```php
// routes/web.php
Route::post('/produits/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
```

#### 7️⃣ Lancer la Migration

```bash
php artisan migrate
```

---

## Workflow de Développement

### 📝 Avant de Commencer

```bash
# 1. Créer une branche
git checkout -b feature/reviews

# 2. Créer un plan
```

### 🛠️ Pendant le Développement

```bash
# 1. Créer les fichiers nécessaires
php artisan make:model Review -m
php artisan make:controller ReviewController
php artisan make:request StoreReviewRequest

# 2. Implémenter la logique
# - Éditer la migration
# - Éditer le modèle
# - Créer le service
# - Éditer le contrôleur

# 3. Lancer les migrations
php artisan migrate

# 4. Créer les tests
php artisan make:test ReviewTest

# 5. Tester
php artisan test

# 6. Tester manuellement
# - Accéder à http://localhost:8000
```

### ✅ Avant de Commiteur

```bash
# 1. Vérifier les tests
php artisan test

# 2. Vérifier la qualité du code
# (utiliser un linter ou formatter)

# 3. Commiteur
git add .
git commit -m "feature: ajouter un système d'avis"

# 4. Pusher
git push origin feature/reviews
```

---

## Dépannage

### ❌ Problème: "CORS error"

**Solution**: Configurer `config/cors.php`

### ❌ Problème: "Unauthorized" (401)

**Solution**: 
```bash
php artisan cache:clear
php artisan config:clear
```

### ❌ Problème: "Class not found"

**Solution**:
```bash
composer dump-autoload
php artisan cache:clear
```

### ❌ Problème: "Database connection failed"

**Solution**:
1. Vérifier les paramètres dans `.env`
2. Vérifier que la base de données existe
3. Vérifier que MySQL est en cours d'exécution

### ❌ Problème: "SQLSTATE[HY000]: General error: 1030"

**Solution**: Vérifier l'espace disque disponible

### ❌ Problème: Assets (CSS/JS) non compilés

**Solution**:
```bash
npm run build
# ou en mode développement
npm run dev
```

---

## Ressources Utiles

### 📚 Documentation Officielle
- [Laravel Docs](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)

### 🎨 Frontend
- [Bootstrap 5](https://getbootstrap.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)

### 🔍 Outils Utiles
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [Laravel Tinker](https://github.com/laravel/tinker)
- [Laravel IDE Helper](https://github.com/barryvdh/laravel-ide-helper)

### 💡 Commandes Utiles Rapides

```bash
# Accéder à la console interactive
php artisan tinker

# Générer quelques modèles de test
>>> Product::factory(10)->create()

# Requête rapide
>>> Product::where('prix', '>', 100)->get()

# Quitter
>>> exit
```

---

**Dernière mise à jour**: 2 mai 2026
