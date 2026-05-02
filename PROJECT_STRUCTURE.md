# 📋 Structure du Projet Stepora

## Architecture Générale

```
stepora/
├── app/                          # Logique métier de l'application
│   ├── Http/
│   │   ├── Controllers/          # Contrôleurs frontend
│   │   ├── Controllers/Admin/    # Contrôleurs administration
│   │   ├── Middleware/           # Middlewares (authentification, etc.)
│   │   ├── Requests/             # Validations des formulaires
│   │   └── Resources/            # Transformations de données API
│   ├── Models/                   # Modèles Eloquent
│   ├── Services/                 # Logique métier (classes utilitaires)
│   ├── Repositories/             # Accès aux données (pattern Repository)
│   ├── Traits/                   # Traits réutilisables
│   ├── Events/                   # Événements de l'application
│   ├── Listeners/                # Écouteurs d'événements
│   ├── Jobs/                     # Jobs/Queues
│   ├── Mail/                     # Mails
│   ├── Enums/                    # Énumérations
│   ├── Exceptions/               # Exceptions personnalisées
│   ├── Helpers/                  # Fonctions helpers
│   └── Providers/                # Service providers
│
├── database/
│   ├── migrations/               # Migrations de la base de données
│   ├── seeders/                  # Seeders (données de test)
│   └── factories/                # Factories (données de test)
│
├── resources/
│   ├── views/
│   │   ├── layouts/              # Layouts principaux
│   │   ├── components/           # Composants Blade réutilisables
│   │   ├── dashboard/            # Vues d'administration
│   │   ├── produits/             # Vues des produits
│   │   ├── panier/               # Vues du panier
│   │   ├── partials/             # Partiels (fragments de pages)
│   │   ├── emails/               # Templates d'emails
│   │   ├── errors/               # Pages d'erreur
│   │   └── welcome.blade.php     # Page d'accueil
│   ├── css/                      # Fichiers CSS
│   └── js/                       # Fichiers JavaScript
│
├── routes/
│   ├── web.php                   # Routes web
│   └── console.php               # Routes console
│
├── config/                       # Fichiers de configuration
├── storage/                      # Stockage (logs, sessions, uploads)
├── bootstrap/                    # Fichiers de démarrage
├── public/                       # Dossier public (assets)
└── tests/                        # Tests unitaires et fonctionnels
```

## Normes de Codage

### 🏷️ Noms de Fichiers et Classes

- **Modèles**: PascalCase + Singulier (ex: `User.php`, `Product.php`)
- **Contrôleurs**: PascalCase + "Controller" (ex: `ProductController.php`)
- **Services**: PascalCase + "Service" (ex: `ProductService.php`)
- **Repositories**: PascalCase + "Repository" (ex: `ProductRepository.php`)
- **Requests**: PascalCase + "Request" (ex: `StoreProductRequest.php`)
- **Resources**: PascalCase + "Resource" (ex: `ProductResource.php`)
- **Traits**: PascalCase + "Trait" (ex: `TimestampsTrait.php`)
- **Events**: PascalCase + "Event" (ex: `OrderCreatedEvent.php`)
- **Listeners**: PascalCase + "Listener" (ex: `SendOrderEmailListener.php`)
- **Jobs**: PascalCase + "Job" (ex: `ProcessOrderJob.php`)
- **Enums**: PascalCase (ex: `OrderStatus.php`)
- **Exceptions**: PascalCase + "Exception" (ex: `ProductNotFoundException.php`)

### 📦 Modèles et Leurs Relations

| Modèle           | Table             | Description                     |
| ---------------- | ----------------- | ------------------------------- |
| **User**         | users             | Utilisateurs (admin et clients) |
| **Client**       | clients           | Informations clients            |
| **Product**      | produits          | Produits du catalogue           |
| **Category**     | categories        | Catégories de produits          |
| **Cart**         | paniers           | Paniers d'achat                 |
| **Order**        | commandes         | Commandes passées               |
| **OrderProduct** | commande_produits | Produits dans les commandes     |
| **Message**      | messages          | Messages de contact             |

### 🔗 Relations Principales

```
User (1) ──→ (M) Order
        └──→ (M) Message
        └──→ (M) Cart

Category (1) ──→ (M) Product

Product (M) ──→ (1) Category
       └──→ (M) OrderProduct

Order (1) ──→ (M) OrderProduct
     └──→ (1) User

OrderProduct (M) ──→ (1) Product
             └──→ (1) Order

Cart (1) ──→ (1) User
```

## Flux de Travail Recommandé

### Créer un Nouveau Contrôleur

```bash
php artisan make:controller --model=Product
```

### Créer un Service Métier

```
app/Services/ProductService.php
```

### Créer une Validation

```bash
php artisan make:request StoreProductRequest
```

### Créer une Ressource API

```bash
php artisan make:resource ProductResource
```

## ✅ Checklist de Qualité

- [ ] Tous les modèles sont en PascalCase singulier
- [ ] Tous les contrôleurs sont suffixés par "Controller"
- [ ] La logique métier est dans Services
- [ ] Les validations utilisent Requests
- [ ] Les relations Eloquent sont correctement définies
- [ ] Les migrations sont nommées explicitement
- [ ] Les routes sont bien organisées par groupe
- [ ] Les views utilisent des composants réutilisables
- [ ] Les tests couvrent les cas critiques
- [ ] La documentation est à jour
