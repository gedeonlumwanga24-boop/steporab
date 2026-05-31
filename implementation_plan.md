# Plan d'Implémentation - Stepora

**Date de création :** 30 mai 2026

---

## Vue d'ensemble

Ce plan d'implémentation couvre deux fonctionnalités majeures :

1. **Upload de vidéo depuis le panneau Admin** avec modification des textes
2. **Tiroir "Ajouté au panier"** avec interaction utilisateur fluide (inspiré Nike)

---

## 1. Upload de Vidéo depuis l'Admin

### ✅ STATUS : DÉJÀ IMPLÉMENTÉ

### 1.1 Objectifs

- [x] Ajouter un formulaire d'upload vidéo dans le panneau admin
- [x] Accepter les fichiers MP4
- [x] Mettre à jour les textes associés ("Les essentiels...", "Pour ceux qui bougent")
- [x] Stocker la vidéo dans le système de fichiers
- [x] Mettre en cache la configuration

**NOTE :** Cette fonctionnalité existe déjà dans `resources/views/admin/config/index.blade.php` avec la section "Paramètres de la Bannière Vidéo (Tendances)"

### 1.2 Structure technique

#### Backend (PHP/Laravel)

- **Fichier cible :** `app/Http/Controllers/Admin/ConfigController.php` (ou créer)
    - Ajouter une méthode `uploadVideo(Request $request)`
    - Valider le fichier (type MP4, taille max)
    - Sauvegarder dans `storage/app/public/videos/`
    - Mettre à jour `SiteConfig` avec le chemin vidéo
- **Base de données :** Table `site_configs`
    - Ajouter colonnes : `video_path`, `video_text_1`, `video_text_2`
    - Exemple :
        - `video_path`: `/storage/videos/hero_video.mp4`
        - `video_text_1`: "Les essentiels pour bien commencer"
        - `video_text_2`: "Pour ceux qui bougent partout"

#### Frontend (Admin)

- **Vue/Formulaire :** `resources/views/admin/config/edit.blade.php`
    - Champ d'upload vidéo (accepter MP4)
    - Deux champs texte pour les légendes
    - Bouton "Enregistrer"
    - Aperçu vidéo après upload

#### Validation

- Type de fichier : `video/mp4`
- Taille max : 50 MB
- Résolution min : 1280x720

### 1.3 Étapes d'implémentation

1. Modifier migration BD pour ajouter colonnes vidéo
2. Créer/modifier le contrôleur Admin
3. Créer le formulaire d'upload
4. Ajouter le endpoint API pour upload
5. Tester l'upload et le stockage

---

## 2. Tiroir "Ajouté au Panier" (Cart Drawer)

### ✅ STATUS : IMPLÉMENTATION COMPLÈTE

### 2.1 Objectifs

- [x] Intercepter les clics "Ajouter au panier" avec Javascript
- [x] Afficher un tiroir depuis le bas (drawer slide-up animation)
- [x] Afficher les détails du produit ajouté
- [x] Proposer deux actions : "Afficher le panier" et "Paiement"
- [x] Permettre la fermeture (croix, clic dehors, ou délai auto)
- [x] Pas de rechargement de page

### 2.2 Design (inspiré Nike)

```
┌─────────────────────────────────┐
│                                 │
│       PAGE PRINCIPALE           │
│                                 │
├─────────────────────────────────┤
│ ╔════════════════════════════╗  │
│ ║  Produit ajouté au panier  ║  │ <- Tiroir glisse de bas
│ ║  ┌──┐                      ║  │
│ ║  │📷│ Nom Produit          ║  │
│ ║  │  │ Quantité: 1          ║  │
│ ║  │  │ Prix: 29.99€         ║  │
│ ║  └──┘                      ║  │
│ ║                            ║  │
│ ║  [Afficher le panier]      ║  │
│ ║  [Procéder au paiement]    ║  │
│ ║  [X]                       ║  │
│ ╚════════════════════════════╝  │
└─────────────────────────────────┘
```

### 2.3 Structure technique

#### Backend (PHP/Laravel)

- **Endpoint API :** `POST /api/cart/add-drawer`
    - Accepter les données du produit (id, quantité)
    - Retourner JSON avec détails produit
    - Pas de redirection, réponse JSON seulement
- **Format réponse JSON :**

```json
{
    "success": true,
    "product": {
        "id": 1,
        "name": "Produit XYZ",
        "price": 29.99,
        "quantity": 1,
        "image": "/storage/products/image.jpg",
        "total": 29.99
    }
}
```

#### Frontend (Javascript/CSS)

- **Fichier JS :** `resources/js/components/CartDrawer.js`
    - Intercepter tous les boutons `.btn-add-to-cart`
    - Émettre une requête AJAX au lieu de formulaire
    - Afficher le tiroir avec animation
    - Gérer les événements de fermeture

- **Fichier CSS :** `resources/css/cart-drawer.css`
    - Animation slide-up (ease-out)
    - Overlay semi-transparent (backdrop)
    - Responsive design
    - Animations boutons hover

#### HTML/Blade Template

- **Tiroir HTML :** `resources/views/components/cart-drawer.blade.php`
    - Structure du tiroir
    - Slots pour produit/actions
    - Boutons action

### 2.4 Comportement interactif

1. **Ajout produit** : Clic sur "Ajouter au panier"
    - Requête AJAX silencieuse
    - Affichage tiroir (300ms animation)
2. **Fermeture** :
    - Clic sur [X] : fermeture immédiate
    - Clic hors du tiroir (overlay) : fermeture
    - Auto-fermeture après 5 secondes (optionnel)
3. **Actions** :
    - "Afficher le panier" → Navigation vers `/panier`
    - "Paiement" → Redirection vers page paiement

### 2.5 Étapes d'implémentation

1. [x] Créer le composant HTML du tiroir - `resources/views/components/cart-drawer.blade.php`
2. [x] Ajouter le CSS et animations - `resources/css/cart-drawer.css`
3. [x] Créer le fichier Javascript (intercepteur + animation) - `resources/js/components/CartDrawer.js`
4. [x] Modifier l'endpoint backend pour retourner JSON - Déjà implémenté dans `PanierController::ajouter`
5. [x] Intégrer CartDrawer dans layout principal - `resources/views/layouts/app.blade.php`
6. [x] Import dans Vite - `resources/css/app.css` et `resources/js/app.js`
7. [ ] Tester l'interaction utilisateur
8. [ ] Tests sur mobile/desktop

---

## 3. Récapitulatif - Fichiers Créés et Modifiés

### 📁 Fichiers Créés

| Fichier                                            | Purpose                           | Lignes |
| -------------------------------------------------- | --------------------------------- | ------ |
| `resources/views/components/cart-drawer.blade.php` | Composant HTML du tiroir          | 55     |
| `resources/css/cart-drawer.css`                    | Styles et animations du tiroir    | 380    |
| `resources/js/components/CartDrawer.js`            | Gestionnaire JavaScript du tiroir | 170    |
| `CART_DRAWER_TEST_GUIDE.md`                        | Guide de test complet             | 180    |

### 📝 Fichiers Modifiés

| Fichier                                 | Modification                                 |
| --------------------------------------- | -------------------------------------------- |
| `resources/css/app.css`                 | Ajout : `@import "./cart-drawer.css";`       |
| `resources/js/app.js`                   | Ajout : `import "./components/CartDrawer";`  |
| `resources/views/layouts/app.blade.php` | Ajout : `@include('components.cart-drawer')` |
| `implementation_plan.md`                | Mise à jour du statut d'implémentation       |

### ⚙️ Fichiers Inchangés (Déjà Compatibles)

- `app/Http/Controllers/PanierController.php` - Retourne déjà du JSON pour AJAX
- `routes/web.php` - Route `/panier/ajouter/{id}` existe déjà
- `resources/views/produits/show.blade.php` - Formulaire compatible

---

## 4. Architecture et Flux de Données

### Flux AJAX

```
1. Utilisateur clique "Ajouter au Panier"
   ↓
2. JavaScript intercepte (CartDrawer.js)
   ↓
3. Envoi FormData via fetch() à /panier/ajouter/{id}
   ↓
4. PanierController::ajouter retourne JSON
   ↓
5. CartDrawerManager reçoit data.product
   ↓
6. Affiche le tiroir avec animation
   ↓
7. Tiroir reste visible (auto-fermeture optionnelle)
```

### Communication Backend ↔ Frontend

**Request :**

```http
POST /panier/ajouter/1 HTTP/1.1
X-Requested-With: XMLHttpRequest
Content-Type: multipart/form-data

csrf_token=...
variant_label=EU 42  (optionnel)
```

**Response (JSON) :**

```json
{
    "success": true,
    "message": "Produit ajouté au panier",
    "cart_count": 3,
    "product": {
        "nom": "Air Force 1",
        "prix": "150 000 CDF",
        "image": "/storage/produits/af1.jpg",
        "category": "Chaussures",
        "quantity": 1,
        "variant": "EU 42"
    }
}
```

---

## 5. Considérations Techniques

### Sécurité

- [ ] Valider les uploads vidéo côté serveur
- [ ] Vérifier les autorisations admin avant d'accepter l'upload
- [ ] Sanctionner les requêtes AJAX non autorisées

### Performance

- [ ] Compresser la vidéo pour web (codec H.264)
- [ ] Implémenter le lazy loading pour la vidéo
- [ ] Cache côté client pour les réponses AJAX

### Compatibilité

- [ ] Tester sur Chrome, Firefox, Safari, Edge
- [ ] Tester sur mobile (iOS/Android)
- [ ] Fallback pour navigateurs anciens

---

## 6. Démarrage et Tests

### 🚀 Lancer le Projet

```bash
# 1. Compiler les assets avec Vite
npm run dev

# 2. Démarrer le serveur Laravel
php artisan serve

# 3. Accéder à l'application
# Naviguer vers http://localhost:8000
```

### ✅ Tester le Tiroir

1. Aller sur une page produit : `/produits/{id}`
2. Cliquer sur "Ajouter au panier"
3. **Attendu :** Le tiroir glisse depuis le bas
4. Tester les fermetures :
    - Clic [X]
    - Clic overlay (zone sombre)
    - Appui ESC

**Guide détaillé :** Voir [CART_DRAWER_TEST_GUIDE.md](CART_DRAWER_TEST_GUIDE.md)

---

## 7. Checklist de Déploiement

- [ ] `npm run dev` compilé sans erreurs
- [ ] Tests manuels effectués (desktop + mobile)
- [ ] Aucune erreur console (F12)
- [ ] AJAX fonctionne (Network tab, voir JSON)
- [ ] Animations smooth (pas de lag)
- [ ] Responsive design OK sur mobile
- [ ] Routes API testées
- [ ] Notifications utilisateur claires
- [ ] Performance acceptable (<100ms AJAX)
- [ ] Code en production prêt

---

## 8. Améliorations Futures

- [ ] Persistance du panier (localStorage)
- [ ] Animation du compteur de produits
- [ ] Sound effect (bip succès)
- [ ] Slider produits suggérés dans le tiroir
- [ ] QR code de partage
- [ ] Intégration chat support directement dans le tiroir
