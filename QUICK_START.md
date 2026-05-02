# 🎯 Guide de Démarrage Rapide - Stepora

## 🚀 Tu es Nouveau? Commence Ici!

### 👋 Étape 1: Vue d'Ensemble (5 min)

Lire ce fichier et:

- [COMPREHENSIVE_README.md](COMPREHENSIVE_README.md) - Vue d'ensemble générale

### 📚 Étape 2: Comprendre la Structure (10 min)

- [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - Architecture complète
- [STRUCTURE_OVERVIEW.md](STRUCTURE_OVERVIEW.md) - Visual overview

### 🎓 Étape 3: Standards de Codage (10 min)

- [CODING_STANDARDS.md](CODING_STANDARDS.md) - Ce qu'il faut respecter

### 🛠️ Étape 4: Développement (10 min)

- [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Comment faire les choses

### ✅ Étape 5: Checklist (5 min)

- [REORGANIZATION_CHECKLIST.md](REORGANIZATION_CHECKLIST.md) - Ce qui a été fait

---

## 🗂️ Index des Fichiers de Documentation

| Fichier                          | Audience | Temps  | Purpose                  |
| -------------------------------- | -------- | ------ | ------------------------ |
| **Ce Fichier**                   | Tous     | 2 min  | Guide de démarrage       |
| [COMPREHENSIVE_README.md](#)     | Tous     | 10 min | Vue générale du projet   |
| [PROJECT_STRUCTURE.md](#)        | Devs     | 15 min | Architecture & structure |
| [STRUCTURE_OVERVIEW.md](#)       | Devs     | 10 min | Vue visuelle & flux      |
| [CODING_STANDARDS.md](#)         | Devs     | 20 min | Standards & patterns     |
| [DEVELOPMENT_GUIDE.md](#)        | Devs     | 20 min | How-to & commandes       |
| [REORGANIZATION_CHECKLIST.md](#) | Leads    | 10 min | Historique de refonte    |

---

## 🎯 Je Veux Faire Quoi?

### 👶 Je Suis Nouveau, Je Veux Comprendre

→ **Lis dans cet ordre:**

1. Ce fichier (vue d'ensemble)
2. [COMPREHENSIVE_README.md](COMPREHENSIVE_README.md)
3. [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)
4. [STRUCTURE_OVERVIEW.md](STRUCTURE_OVERVIEW.md)

**Temps estimé**: 45 min

---

### 🚀 Je Veux Créer une Nouvelle Fonctionnalité

→ **Lis ces fichiers:**

1. [CODING_STANDARDS.md](CODING_STANDARDS.md) - Patterns
2. [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Étapes
3. Cherche un exemple similaire dans le code

**Temps estimé**: 15 min + implémentation

---

### 🔧 Je Dois Corriger un Bug

→ **Cherche d'abord:**

1. Le modèle concerné dans `app/Models/`
2. Le service associé dans `app/Services/`
3. Le contrôleur dans `app/Http/Controllers/`
4. [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Debugging

**Temps estimé**: Variable

---

### 📊 Je Veux Optimiser le Code

→ **Consulte:**

1. [CODING_STANDARDS.md](CODING_STANDARDS.md) - Performance section
2. [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) - Architecture
3. Regarde les Services pour la logique réutilisable

**Temps estimé**: Variable

---

### ✅ Je Veux Ajouter des Tests

→ **Va dans:**

1. [CODING_STANDARDS.md](CODING_STANDARDS.md) - Tests section
2. [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Tests

**Temps estimé**: 15 min setup

---

### 🚢 Je Veux Déployer

→ **Lis:**

1. [COMPREHENSIVE_README.md](COMPREHENSIVE_README.md) - Deployment section
2. [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Commandes utiles

**Temps estimé**: 10 min

---

## 🏗️ Structure Rapide

```
app/
├── Http/
│   ├── Controllers/     ← Les points d'entrée
│   ├── Requests/        ← Les validations
│   └── Resources/       ← Les transformations API
├── Models/              ← Les données
├── Services/            ← LA logique métier ⭐
├── Traits/              ← Code réutilisable
├── Enums/               ← Valeurs énumérées
├── Exceptions/          ← Erreurs custom
└── Helpers/             ← Utilitaires
```

---

## 🛠️ Services Disponibles

| Service            | Utilisation                        |
| ------------------ | ---------------------------------- |
| **ProductService** | Tout ce qui concerne les produits  |
| **CartService**    | Tout ce qui concerne le panier     |
| **OrderService**   | Tout ce qui concerne les commandes |
| **MessageService** | Tout ce qui concerne les messages  |

---

## 💡 Patterns Clés

### 1️⃣ Controller

```php
public function store(StoreProductRequest $request)
{
    $product = $this->productService->createProduct($request->validated());
}
```

### 2️⃣ Service

```php
public function createProduct(array $data): Product
{
    return Product::create($data);
}
```

### 3️⃣ Exception

```php
if (!$product) {
    throw new ProductNotFoundException($id);
}
```

### 4️⃣ Enum

```php
$order->status = OrderStatus::CONFIRMED;
echo $order->status->label(); // "Confirmée"
```

### 5️⃣ Helper

```php
AppHelper::formatPrice(99.99); // "99,99 €"
```

---

## 📚 Ressources Importantes

### Documentation Laravel

- [Laravel Official Docs](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Blade Templates](https://laravel.com/docs/blade)

### Dans Ce Projet

- Services réutilisables
- Validations centralisées
- Traits réutilisables

---

## ⚡ Commandes Essentielles

```bash
# Serveur de développement
php artisan serve
npm run dev

# Base de données
php artisan migrate
php artisan db:seed
php artisan migrate:refresh

# Créer des fichiers
php artisan make:model NomModel -m
php artisan make:controller NomController
php artisan make:request NomRequest

# Tests
php artisan test

# Cache
php artisan cache:clear
php artisan config:clear
```

---

## 🤔 Questions Fréquentes

### Q: Où mets-je ma logique métier?

**R**: Dans un Service! Voir [CODING_STANDARDS.md](CODING_STANDARDS.md)

### Q: Où valides-je les données?

**R**: Dans un FormRequest! Voir les fichiers dans `app/Http/Requests/`

### Q: Comment crée-je un nouveau modèle?

**R**: Suis le guide dans [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)

### Q: Quels services peut-je utiliser?

**R**: Voir la section Services du [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)

### Q: Comment je testais?

**R**: Lis la section Tests du [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)

---

## 🚦 Avant de Coder

### ✅ Checklist

- [ ] J'ai lu [COMPREHENSIVE_README.md](COMPREHENSIVE_README.md)
- [ ] J'ai compris [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)
- [ ] Je connais les [CODING_STANDARDS.md](CODING_STANDARDS.md)
- [ ] J'ai suivi [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)
- [ ] Je respecte les patterns

---

## 👥 Qui Fait Quoi

| Rôle                | Références                                                             |
| ------------------- | ---------------------------------------------------------------------- |
| **Nouveau Dev**     | Tous les fichiers dans cet ordre                                       |
| **Dev Expérimenté** | [CODING_STANDARDS.md](CODING_STANDARDS.md) + IDE                       |
| **Lead**            | [REORGANIZATION_CHECKLIST.md](REORGANIZATION_CHECKLIST.md) + Standards |
| **DevOps**          | [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Deployment              |

---

## 🎯 Cycle de Développement Typique

```
1. Planifier
   ↓
2. Lire les standards [CODING_STANDARDS.md]
   ↓
3. Suivre le guide [DEVELOPMENT_GUIDE.md]
   ↓
4. Implémenter
   - Créer Service
   - Créer FormRequest
   - Créer Controller
   - Créer View
   ↓
5. Tester
   ↓
6. Commit & Push
   ↓
7. PR & Review
```

---

## 🎓 Niveaux de Compréhension

### 🟢 Niveau 1: Basics (1-2h)

- [ ] Lis COMPREHENSIVE_README.md
- [ ] Comprends PROJECT_STRUCTURE.md
- [ ] Peux naviguer dans le code

### 🟡 Niveau 2: Standards (2-3h)

- [ ] Lis CODING_STANDARDS.md
- [ ] Comprends les patterns
- [ ] Peux écrire du code standard

### 🔴 Niveau 3: Expert (1 semaine)

- [ ] Maîtrise DEVELOPMENT_GUIDE.md
- [ ] Peux créer des Services
- [ ] Peux mentorer d'autres

---

## 🆘 Je Suis Bloqué

### Étape 1: Lis la Documentation

1. Cherche le sujet dans [CODING_STANDARDS.md](CODING_STANDARDS.md)
2. Cherche les commandes dans [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)
3. Cherche la structure dans [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)

### Étape 2: Trouve un Exemple

1. Regarde un Service similaire
2. Regarde un Controller similaire
3. Copie le pattern

### Étape 3: Dépanne

1. Lis la section Debugging dans [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)
2. Vérifie la syntaxe
3. Teste avec `php artisan tinker`

---

## 📞 Ressources Rapides

### À Comprendre

- [x] Architecture → [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)
- [x] Standards → [CODING_STANDARDS.md](CODING_STANDARDS.md)
- [x] Développement → [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md)
- [x] Overview → [STRUCTURE_OVERVIEW.md](STRUCTURE_OVERVIEW.md)

### À Utiliser

- **Services**: `app/Services/`
- **Validations**: `app/Http/Requests/`
- **Helpers**: `app/Helpers/AppHelper.php`
- **Traits**: `app/Traits/`

---

## ✨ Tu es Prêt!

### Les 3 Étapes pour Démarrer

1. **Lis** [COMPREHENSIVE_README.md](COMPREHENSIVE_README.md) (10 min)
2. **Comprends** [PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) (15 min)
3. **Suis** [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) (10 min)

### Puis Code!

Bienvenue dans le projet Stepora! 🚀

---

**Dernière mise à jour**: 2 mai 2026  
**Version**: 1.0.0  
**Prochaine révision**: 31 mai 2026

Bon développement! 🎉
