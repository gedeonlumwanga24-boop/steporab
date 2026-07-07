<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Web\ProduitController;
use App\Http\Controllers\Web\PanierController;
use App\Http\Controllers\Web\CommandeController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Auth\GoogleController;
use App\Http\Controllers\Web\ProfileController;

// Admin Controllers
use App\Http\Controllers\Web\Admin\ProduitController as AdminProduitController;
use App\Http\Controllers\Web\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Web\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Web\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Web\Admin\StatsController;
use App\Http\Controllers\Web\Admin\ErrorLogController;
use App\Http\Controllers\Web\Admin\AdminUserController;
use App\Http\Controllers\Web\Admin\NewsletterController as AdminNewsletterController;


// -------------------- FRONT-END --------------------

// Accueil
Route::get('/', function () {
    $trendingProducts = \App\Models\Produit::latest()->take(4)->get();
    $configs = \App\Models\SiteConfig::pluck('value', 'key');
    $categories = \App\Models\Category::navigation()->get();
    return view('welcome', compact('trendingProducts', 'configs', 'categories'));
})->name('home');

// A propos
Route::get('/apropos', function () {
    $configs = \App\Models\SiteConfig::pluck('value', 'key');
    return view('apropos', compact('configs'));
})->name('apropos');

// Serve images stored in resources/images when the public/images folder is not present
Route::get('/images/{path}', function ($path) {
    $imagesDir = realpath(resource_path('images'));
    $requestedFile = realpath(resource_path('images/' . $path));

    if (!$imagesDir || !$requestedFile || !str_starts_with($requestedFile, $imagesDir)) {
        abort(404);
    }

    return response()->file($requestedFile);
})->where('path', '.*');

// Produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
Route::get('/produits/{id}', [ProduitController::class, 'show'])->name('produits.show');

// Panier
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::post('/panier/ajouter/{id}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
Route::get('/panier/update/{id}/{action}', [PanierController::class, 'update'])->name('panier.update');
Route::get('/panier/supprimer/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
Route::get('/panier/vider', [PanierController::class, 'vider'])->name('panier.vider');

// Commande & Paiement
Route::middleware('auth')->group(function () {
    Route::post('/commande', [CommandeController::class, 'checkout'])->name('commande.store');
    Route::get('/commande/{commande}/paiement', [CommandeController::class, 'showPayment'])->name('commande.paiement');

    // Routes PawaPay (paiement automatique Mobile Money)
    Route::post('/commande/{commande}/pawapay/initier', [CommandeController::class, 'initiatePawaPay'])->name('commande.pawapay.initiate');
    Route::get('/commande/{commande}/pawapay/attente', [CommandeController::class, 'waitingPayment'])->name('commande.pawapay.waiting');
    Route::get('/commande/{commande}/pawapay/statut', [CommandeController::class, 'checkPaymentStatus'])->name('commande.pawapay.status');
    Route::get('/commande/{commande}/pawapay/succes', [CommandeController::class, 'paymentSuccess'])->name('commande.paiement.success');
    Route::get('/commande/{commande}/pawapay/echec', [CommandeController::class, 'paymentFailed'])->name('commande.paiement.failed');

    // Ancienne logique (upload preuve manuelle) - conservée pour compatibilité
    Route::get('/commande/{commande}/preuve', [CommandeController::class, 'showProof'])->name('commande.preuve');
    Route::post('/commande/{commande}/preuve', [CommandeController::class, 'submitProof'])->name('commande.submitProof');
    Route::get('/commande/{commande}/confirmation', [CommandeController::class, 'confirmation'])->name('commande.confirmation');
});

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


// Auth
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Compte Client & Messagerie
Route::middleware('auth')->group(function () {
    Route::get('/compte', [ProfileController::class, 'show'])->name('compte.show');
    Route::get('/compte/modifier', [ProfileController::class, 'edit'])->name('compte.edit');
    Route::put('/compte/modifier', [ProfileController::class, 'update'])->name('compte.update');
    
    Route::get('/messagerie', [ProfileController::class, 'messages'])->name('messagerie.index');
    Route::post('/messagerie', [ProfileController::class, 'sendMessage'])->name('messagerie.store');
});

// -------------------- ADMIN --------------------

Route::prefix('admin')->middleware(['admin'])->group(function () {

    // Dashboard
    Route::get('/', [StatsController::class, 'index'])->name('admin.dashboard');

    // Produits
    Route::resource('produits', AdminProduitController::class)->names('admin.produits');

    // Catégories
    Route::resource('categories', \App\Http\Controllers\Web\Admin\CategoryController::class)->names('admin.categories');

    // Commandes
    Route::get('commandes/paiements-en-attente', [AdminCommandeController::class, 'pendingPayments'])->name('admin.commandes.paiements');
    Route::post('commandes/{commande}/valider', [AdminCommandeController::class, 'validatePayment'])->name('admin.commandes.valider');
    Route::post('commandes/{commande}/refuser', [AdminCommandeController::class, 'refusePayment'])->name('admin.commandes.refuser');
    Route::post('commandes/{commande}/sync-pawapay', [AdminCommandeController::class, 'syncPawaPay'])->name('admin.commandes.sync-pawapay');
    Route::resource('commandes', AdminCommandeController::class)->names('admin.commandes');

    // Clients
    Route::post('clients/{id}/restore', [AdminClientController::class, 'restore'])->name('admin.clients.restore');
    Route::resource('clients', AdminClientController::class)->names('admin.clients');

    // Messages
    Route::post('messages/{message}/reply', [AdminMessageController::class, 'reply'])->name('admin.messages.reply');
    Route::resource('messages', AdminMessageController::class)->names('admin.messages');

    // Configuration du site
    Route::get('config', [\App\Http\Controllers\Web\Admin\ConfigController::class, 'index'])->name('admin.config.index');
    Route::post('config', [\App\Http\Controllers\Web\Admin\ConfigController::class, 'update'])->name('admin.config.update');

    // Journal des erreurs
    Route::resource('errors', ErrorLogController::class)
        ->except(['create', 'store', 'edit'])
        ->names('admin.errors');

    // Gestion des administrateurs
    Route::get('admins', [AdminUserController::class, 'index'])->name('admin.admins.index');
    Route::get('admins/create', [AdminUserController::class, 'create'])->name('admin.admins.create');
    Route::post('admins', [AdminUserController::class, 'store'])->name('admin.admins.store');
    Route::delete('admins/{user}', [AdminUserController::class, 'destroy'])->name('admin.admins.destroy');

    // Newsletter / Notifications clients
    Route::get('newsletter', [AdminNewsletterController::class, 'create'])->name('admin.newsletter.create');
    Route::post('newsletter', [AdminNewsletterController::class, 'send'])->name('admin.newsletter.send');
});
