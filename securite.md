# 🔐 Documentation de Sécurité — Stepora E-commerce

Ce document récapitule l'ensemble des mesures de sécurité mises en place sur l'application Laravel Stepora pour protéger les utilisateurs, les données et l'infrastructure serveur.

## 1. Protections contre les Vulnérabilités Web Standard (OWASP)

### 1.1. Injection SQL (SQLi)
- **Protection via Eloquent ORM** : L'application n'utilise aucune requête SQL brute (`DB::raw()`, `DB::statement()`). Toutes les interactions avec la base de données passent par l'ORM Eloquent de Laravel qui utilise nativement des requêtes préparées (PDO Parameter Binding). Cela rend les injections SQL impossibles.

### 1.2. Cross-Site Scripting (XSS)
- **Échappement automatique (Blade)** : Toutes les données affichées dans les vues utilisent la syntaxe `{{ $variable }}`, qui exécute automatiquement un `htmlspecialchars` en PHP.
- **Vues spécifiques sécurisées** : Les rares endroits où la syntaxe `{!! !!}` est utilisée (pour du JSON ou du texte formaté) sont protégés via les fonctions `e()` (escape) ou `json_encode()` qui encode les caractères dangereux.
- **Entête Anti-Sniffing** : `X-Content-Type-Options: nosniff` (actif via SecurityHeaders) empêche le navigateur d'interpréter un fichier malveillant comme un script.

### 1.3. Cross-Site Request Forgery (CSRF)
- **Protection CSRF Globale** : Toutes les requêtes web (POST, PUT, DELETE) qui modifient l'état de l'application nécessitent un jeton `@csrf`.
- **API Stateful** : Les requêtes API provenant de l'application frontale locale sont protégées par le middleware `EnsureFrontendRequestsAreStateful` de Sanctum.

### 1.4. Clickjacking
- **Entête HTTP `X-Frame-Options: SAMEORIGIN`** : Empêche un site tiers malveillant d'intégrer Stepora dans une `<iframe>` transparente pour voler des clics utilisateurs.

## 2. Authentification et Gestion des Accès

### 2.1. Sécurité des Mots de Passe
- **Hashage fort** : Les mots de passe sont hashés avec Bcrypt (configuré par défaut dans Laravel) via le casting `$casts = ['password' => 'hashed']` dans le modèle `User`. Les mots de passe en clair ne sont **jamais** stockés.
- **Protection Mass Assignment** : Le champ `role` a été retiré de la liste `$fillable` du modèle `User`. Un attaquant ne peut donc pas forcer la création d'un compte administrateur en falsifiant une requête HTTP d'inscription.

### 2.2. Gestion des Rôles (Spatie)
- **Middlewares restrictifs** : Les zones sensibles sont protégées par des middlewares spécialisés (`admin`, `manager`, `customer`).
- **Exemple** : La route `/admin/*` requiert à la fois d'être connecté et d'avoir le rôle `admin` ou `manager`.

### 2.3. API & Jetons (Sanctum)
- **Tokens API** : L'authentification mobile (V1) utilise Laravel Sanctum. Les jetons ont une durée de vie limitée et l'ancien jeton est systématiquement révoqué lors d'une nouvelle connexion.
- **Rate Limiting (Anti Brute-Force)** : Les routes de connexion et d'inscription (`/api/v1/auth/*`) sont protégées par un *Throttle* limitant à **10 tentatives par minute** par adresse IP.

### 2.4. OAuth (Google Login)
- **Stateless & Try/Catch** : L'intégration avec Google OAuth gère correctement la vérification des clés et capte les erreurs silencieusement si l'utilisateur annule le processus, sans exposer le code source.

## 3. Configuration et Environnement (.env)

### 3.1. Environnement de Production (Recommandations Appliquées)
- **`LOG_LEVEL=warning`** : Le niveau de logs a été relevé de `debug` à `warning` pour éviter que des données sensibles (comme des mots de passe en clair saisis par erreur) ne soient écrites dans les fichiers `.log` du serveur en cas d'erreur.
- **`SESSION_SECURE_COOKIE=true`** : Oblige le navigateur à n'envoyer les cookies de session que via des connexions chiffrées (HTTPS), empêchant le vol de session sur les réseaux Wi-Fi publics.
- **Telescope Désactivé** : `TELESCOPE_ENABLED=false` garantit que l'outil de debug Telescope ne soit pas accessible publiquement, protégeant ainsi l'historique des requêtes et des bases de données.
- *(Note : L'option `APP_DEBUG=true` est conservée active pour le confort du développement local, mais devra passer à `false` sur le vrai serveur).*

## 4. Politique de Headers HTTP (`SecurityHeaders.php`)

Un middleware global (`SecurityHeaders`) a été injecté dans `bootstrap/app.php` pour ajouter les entêtes suivants à **chaque réponse HTTP** :

| Entête | Valeur | Rôle |
|---|---|---|
| `X-Frame-Options` | `SAMEORIGIN` | Bloque le Clickjacking. |
| `X-XSS-Protection` | `1; mode=block` | Active le filtre XSS natif des anciens navigateurs. |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Ne transmet pas l'URL complète lors d'un clic sortant. |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=()` | Interdit à l'application d'accéder à la caméra ou au GPS du client (sauf demande explicite). |

*(Le header Content-Security-Policy strict a été désactivé temporairement en local pour autoriser le chargement local des fichiers CSS/Vite sans problème).*

## 5. Bonnes Pratiques Complémentaires

- **CORS (Cross-Origin Resource Sharing)** : Le fichier `config/cors.php` a été publié et est prêt à être configuré pour contrôler quels domaines externes ont le droit de requêter l'API Stepora (utile plus tard pour PawaPay ou une App Mobile).
- **Vérification Email** : L'interface `MustVerifyEmail` est activée sur le modèle `User` pour s'assurer que les utilisateurs fournissent des adresses valides lors de leur inscription.
- **Validation stricte** : Toutes les entrées API passent par des classes `FormRequests` strictes (ex: `StoreOrderRequest`, `StoreProductRequest`) qui valident les types de données, les longueurs minimales/maximales, et l'existence en base de données avant tout traitement.
