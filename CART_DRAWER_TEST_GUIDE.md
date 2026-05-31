# Guide de Test - Cart Drawer

## ✅ Implémentation Complète

Le tiroir "Ajouté au Panier" a été entièrement implémenté avec les composants suivants :

### Fichiers Créés/Modifiés

#### 1. **Composant HTML** - `resources/views/components/cart-drawer.blade.php`

- Structure du tiroir avec image, détails produit, prix
- Boutons d'action (Afficher le panier / Procéder au paiement)
- Message de succès animé
- Bouton de fermeture (X)

#### 2. **CSS & Animations** - `resources/css/cart-drawer.css`

- Animation slide-up depuis le bas (ease-out)
- Overlay semi-transparent au clic dehors
- Design responsive (mobile/desktop)
- Animations boutons hover
- Scrollbar personnalisée

#### 3. **JavaScript Manager** - `resources/js/components/CartDrawer.js`

- Classe `CartDrawerManager` gère toute l'interaction
- Intercepte les formulaires "Ajouter au panier"
- Envoie requête AJAX (pas de reload page)
- Affiche le tiroir avec les données du produit
- Fermeture : croix / clic dehors / ESC
- Auto-fermeture optionnelle (ligne 120)

#### 4. **Intégrations**

- `resources/css/app.css` - Import du CSS CartDrawer
- `resources/js/app.js` - Import du CartDrawer.js
- `resources/views/layouts/app.blade.php` - Inclusion du composant

---

## 🧪 Comment Tester

### 1️⃣ **Démarrer le serveur**

```bash
php artisan serve
```

### 2️⃣ **Accéder à une page produit**

- Naviguer vers : `http://localhost:8000/produits/{id}`
- Voir un produit avec le bouton "Ajouter au panier"

### 3️⃣ **Tester l'ajout**

- Cliquer sur "Ajouter au panier"
- ✨ **Résultat attendu :**
    - La page NE se recharge PAS
    - Un tiroir glisse depuis le bas
    - Le tiroir affiche :
        - Image du produit
        - Nom et catégorie
        - Prix
        - Quantité
        - Taille/Variante (le cas échéant)
        - Message de succès vert

### 4️⃣ **Tester les interactions**

- **Fermeture croix** : Cliquer le [X] → Le tiroir disparaît
- **Fermeture clic dehors** : Cliquer sur l'overlay sombre → Tiroir disparaît
- **Fermeture ESC** : Appuyer sur Echap → Tiroir disparaît
- **Bouton "Afficher le panier"** : Navigue vers `/panier`
- **Bouton "Paiement"** : Navigue vers la page de commande

### 5️⃣ **Tester sur mobile**

- F12 (DevTools) → Device Toolbar
- Sélectionner un appareil mobile (iPhone, Pixel)
- Répéter les étapes 3-4
- Vérifier que le tiroir occupe bien toute la largeur

---

## 🎯 Comportement Attendu

| Action                      | Résultat                               |
| --------------------------- | -------------------------------------- |
| Clic "Ajouter au panier"    | Tiroir glisse depuis bas (300ms)       |
| Clic sur [X]                | Fermeture immédiate (300ms)            |
| Clic overlay (sombre)       | Fermeture immédiate (300ms)            |
| Appui ESC                   | Fermeture immédiate (300ms)            |
| Appui Espace                | Aucun effet (comportement normal)      |
| Ajout produit avec variante | Affiche la taille/couleur sélectionnée |
| Panier déjà plein           | Les quantités se cumulent (backend)    |

---

## ⚙️ Configuration Optionnelle

### Auto-fermeture Activée

Si vous voulez que le tiroir se ferme **automatiquement après 5 secondes** :

```javascript
// Dans CartDrawer.js, ligne 60
this.displayProduct(data.product, data.cart_count);
this.open();
this.autoClose(5000); // ← Décommenter cette ligne
```

### Adapter les Routes

Si vos routes de "Panier" ou "Commande" sont différentes, modifiez :

```blade
<!-- Dans cart-drawer.blade.php -->
<a href="{{ route('panier.index') }}" ...>  <!-- Vérifier nom route -->
<a href="{{ route('commande.index') }}" ...> <!-- Vérifier nom route -->
```

---

## 🐛 Dépannage

### Le tiroir n'apparaît pas ?

1. Vérifier que Vite est en train de compiler (`npm run dev`)
2. Ouvrir DevTools (F12) → Console
3. Chercher les erreurs JavaScript
4. Vérifier que le formulaire a la classe/action correcte

### Le formulaire se soumet normalement (reload page) ?

1. Vérifier que le formulaire utilise `POST`
2. L'action doit être `/panier/ajouter/{id}`
3. S'assurer que le JS est bien chargé (DevTools → Network)

### Les données du produit n'apparaissent pas ?

1. Ouvrir DevTools → Network
2. Cliquer "Ajouter au panier"
3. Vérifier la réponse JSON :
    - Elle doit contenir `product.nom`, `product.prix`, etc.
    - Voir `PanierController::ajouter` ligne 70-79

### L'overlay ne se ferme pas ?

1. S'assurer que la classe `.cart-drawer-overlay` existe
2. Vérifier que l'event listener est actif (DevTools → Elements → .cart-drawer-overlay)

---

## 📝 Notes Importantes

- **Pas de rechargement de page** : Le panier se met à jour via AJAX
- **Compatible mobile** : Design responsive avec Tailwind + CSS custom
- **Accessibilité** : Boutons avec aria-label, fermeture au clavier
- **Performance** : Animation GPU-accélérée (transform/opacity)

---

## 📌 Prochaines Étapes

1. Tester sur différents navigateurs (Chrome, Firefox, Safari)
2. Tester sur mobile réel (pas seulement DevTools)
3. Vérifier que le compteur du panier se met à jour (si applicable)
4. Adapter les styles si nécessaire (couleurs, polices)
5. Ajouter de la branding (logo, couleurs de la marque)

---

**Statut :** ✅ Implémentation terminée et prête à tester
**Données du rapport de test :** [À remplir après test]
