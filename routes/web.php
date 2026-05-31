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
    return view('apropos');
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

// Commande
Route::post('/commande', [CommandeController::class, 'checkout'])->name('commande.store');
Route::get('/commande/succes', function() {
    return view('commande.succes');
})->name('commande.succes');

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

// Compte Client
Route::middleware('auth')->group(function () {
    Route::get('/compte', [ProfileController::class, 'show'])->name('compte.show');
    Route::get('/compte/modifier', [ProfileController::class, 'edit'])->name('compte.edit');
    Route::put('/compte/modifier', [ProfileController::class, 'update'])->name('compte.update');
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
    Route::resource('commandes', AdminCommandeController::class)->names('admin.commandes');

    // Clients
    Route::resource('clients', AdminClientController::class)->names('admin.clients');

    // Messages
    Route::resource('messages', AdminMessageController::class)->names('admin.messages');

    // Configuration du site
    Route::get('config', [\App\Http\Controllers\Web\Admin\ConfigController::class, 'index'])->name('admin.config.index');
    Route::post('config', [\App\Http\Controllers\Web\Admin\ConfigController::class, 'update'])->name('admin.config.update');
});
