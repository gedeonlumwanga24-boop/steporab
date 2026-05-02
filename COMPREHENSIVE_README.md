# 🛍️ Stepora - Plateforme E-Commerce

## 📝 Description

Stepora est une plateforme e-commerce moderne construite avec **Laravel 11** et **Blade**. Elle permet aux utilisateurs de parcourir, consulter et acheter des produits, avec un système d'administration complet pour gérer les commandes, les produits et les clients.

## ✨ Caractéristiques

- 🛒 Catalogue de produits avec catégories
- 🛒 Système de panier persistant
- 📦 Gestion des commandes
- 👥 Gestion des clients
- 💬 Système de messages/contact
- 👨‍💼 Tableau de bord administrateur
- 📊 Statistiques et rapports
- 🔐 Authentification et autorisation
- 📱 Design responsive
- ⚡ Performance optimisée

## 🚀 Démarrage Rapide

### Prérequis

- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer

### Installation

```bash
# 1. Cloner le projet
git clone <repository>
cd stepora

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données
# Éditer .env avec vos paramètres MySQL
php artisan migrate

# 5. Lancer le projet
php artisan serve
npm run dev

# Accéder à http://localhost:8000
```

## 📁 Structure du Projet

```
stepora/
├── app/                          # Cœur de l'application
│   ├── Http/Controllers/         # Contrôleurs
│   ├── Http/Requests/            # Validations
│   ├── Http/Resources/           # Transformations API
│   ├── Models/                   # Modèles Eloquent
│   ├── Services/                 # Logique métier
│   ├── Repositories/             # Accès aux données
│   ├── Traits/                   # Traits réutilisables
│   ├── Enums/                    # Énumérations
│   ├── Exceptions/               # Exceptions personnalisées
│   ├── Helpers/                  # Fonctions helper
│   └── Providers/                # Service providers
├── database/                      # Migrations & Seeders
├── resources/                     # Vues & Assets
│   ├── views/
│   ├── css/
│   └── js/
├── routes/                        # Routes de l'application
├── tests/                         # Tests unitaires & fonctionnels
├── public/                        # Dossier public
├── storage/                       # Fichiers générés
├── PROJECT_STRUCTURE.md          # Documentation structure
├── CODING_STANDARDS.md           # Standards de codage
└── DEVELOPMENT_GUIDE.md          # Guide de développement
```

## 🗄️ Modèles de Données

### Relations Principales

```
User (1) ──→ (M) Order
     └──→ (M) Message
     └──→ (M) Cart

Category (1) ──→ (M) Product
Product (M) ──→ (1) Category
Product (M) ──→ (M) Order (via commande_produit)

Order (1) ──→ (M) OrderProduct
     └──→ (1) User

Cart (1) ──→ (1) User
```

### Modèles Disponibles

- **User**: Utilisateurs (admin/client)
- **Client**: Informations client
- **Product**: Produits du catalogue
- **Category**: Catégories
- **Cart**: Paniers d'achat
- **Order**: Commandes
- **OrderProduct**: Produits dans les commandes
- **Message**: Messages de contact

## 🔧 Commandes Utiles

```bash
# Développement
php artisan serve                 # Lancer le serveur
npm run dev                       # Compiler les assets
npm run build                     # Build pour production

# Base de données
php artisan migrate               # Lancer les migrations
php artisan db:seed               # Seeder la BD
php artisan migrate:refresh       # Réinitialiser la BD

# Génération de fichiers
php artisan make:model [Nom]      # Créer un modèle
php artisan make:controller [Nom] # Créer un contrôleur
php artisan make:request [Nom]    # Créer une validation
php artisan make:resource [Nom]   # Créer une ressource API

# Tests
php artisan test                  # Lancer tous les tests
php artisan test --watch          # Mode watch

# Cache & Config
php artisan cache:clear           # Vider le cache
php artisan config:clear          # Vider la config
php artisan config:cache          # Mettre en cache la config

# Autres
php artisan tinker                # Console interactive
php artisan route:list            # Voir toutes les routes
```

## 📚 Documentation

- **[PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)** - Architecture et structure du projet
- **[CODING_STANDARDS.md](CODING_STANDARDS.md)** - Standards de codage et bonnes pratiques
- **[DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)** - Guide de développement complet

## 🔐 Sécurité

- Validation de toutes les données utilisateur
- CSRF protection automatique
- Authorization avec Policies
- Mass assignment protection
- SQL injection prevention
- XSS prevention avec Blade
- Password hashing avec Bcrypt
- Rate limiting sur les routes sensibles

## 🚀 Déploiement

### Production

```bash
# 1. Build des assets
npm run build

# 2. Mettre en cache les configurations
php artisan config:cache
php artisan route:cache

# 3. Migrer la BD
php artisan migrate --force

# 4. Définir les permissions
chmod -R 775 storage bootstrap/cache
```

## 📊 Architecture et Patterns

### MVC + Services

```
Route → Controller → Service → Model → Database
                ↓
           FormRequest (Validation)
                ↓
           Response/Resource
```

### Dépendances Injectées

```php
public function __construct(private ProductService $productService) {}
```

### Exception Handling

```php
try {
    $product = $this->productService->getProduct($id);
} catch (ProductNotFoundException $e) {
    return back()->with('error', $e->getMessage());
}
```

## 🧪 Tests

```bash
# Lancer les tests
php artisan test

# Avec couverture
php artisan test --coverage

# Tests spécifiques
php artisan test tests/Feature/ProductTest.php

# Mode watch
php artisan test --watch
```

## 🎨 Frontend

- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5 (configurable)
- **JavaScript**: Vanilla + Alpine.js
- **Asset Bundler**: Vite

### Structuration des Views

```
resources/views/
├── layouts/          # Layouts principaux
├── components/       # Composants Blade
├── dashboard/        # Admin dashboard
├── produits/         # Pages produits
├── panier/           # Panier & commande
├── partials/         # Fragments réutilisables
├── emails/           # Templates emails
├── errors/           # Pages d'erreur
└── welcome.blade.php # Accueil
```

## 📦 Dépendances Principales

### Backend

- Laravel 11
- Laravel Tinker
- Carbon (dates)
- Faker (tests)

### Frontend

- Bootstrap 5
- Alpine.js
- Vite

### Testing

- PHPUnit
- Laravel Pest (optionnel)

## 🔄 Workflow de Développement Recommandé

### 1. Planifier

- Définir les modèles
- Planifier les relations
- Lister les fonctionnalités

### 2. Implémenter

- Créer les modèles et migrations
- Créer les services
- Créer les contrôleurs
- Créer les validations
- Créer les vues

### 3. Tester

- Écrire les tests unitaires
- Écrire les tests fonctionnels
- Tester manuellement

### 4. Déployer

- Merger sur main/master
- Déployer sur production
- Monitorer

## 🐛 Dépannage

### Problème: "Class not found"

```bash
composer dump-autoload
php artisan cache:clear
```

### Problème: "CORS error"

Vérifier `config/cors.php`

### Problème: "Database connection failed"

Vérifier les paramètres `.env` et que MySQL est actif

### Problème: Assets non compilés

```bash
npm run build
```

## 🤝 Contributing

1. Fork le projet
2. Créer une branche (`git checkout -b feature/amazing`)
3. Commit les changements (`git commit -m 'feature: amazing'`)
4. Push vers la branche (`git push origin feature/amazing`)
5. Ouvrir une Pull Request

## 📝 Convention de Commits

```
feat:     nouvelle fonctionnalité
fix:      correction de bug
docs:     documentation
style:    formatage du code
refactor: refactorisation
perf:     amélioration de performance
test:     ajout de tests
chore:    maintenance
```

## 📄 License

Propriétaire - Stepora

## 📧 Contact

Pour toute question ou suggestion:

- Email: contact@stepora.local
- Issues: Créer une issue sur GitHub

## 🎯 Roadmap

- [ ] Système d'évaluations (reviews)
- [ ] Panier avec session persistante
- [ ] Système de wishlist
- [ ] Notifications en temps réel
- [ ] API REST complète
- [ ] Intégration paiements (Stripe/PayPal)
- [ ] SEO optimization
- [ ] Multi-langue
- [ ] Dark mode
- [ ] Progressive Web App (PWA)

---

**Dernière mise à jour**: 2 mai 2026

**Développeur**: L'équipe Stepora

**Version**: 1.0.0
