
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProduitController as AdminProduitController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\StatsController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController as FrontCommandeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CommandeController;


Route::get('/produits', [ProduitController::class, 'index']);
Route::get('/produits/{id}', [ProduitController::class, 'show']);
Route::get('/panier', [PanierController::class, 'index']);
Route::get('/panier/ajouter/{id}', [PanierController::class, 'ajouter']);
Route::get('/panier/update/{id}/{action}', [PanierController::class, 'update']);
Route::get('/panier/supprimer/{id}', [PanierController::class, 'supprimer']);
Route::get('/panier/vider', [PanierController::class, 'vider']);
Route::post('/checkout', [CommandeController::class, 'checkout']);
Route::post('/checkout', [CommandeController::class, 'checkout'])->middleware('auth');


// -------------------- FRONT-END --------------------

// Page d'accueil
Route::get('/', function () {
    return view('welcome'); // Accueil STEPORA
});

// Liste des produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');

// Voir un produit
Route::get('/produits/{id}', [ProduitController::class, 'show'])->name('produits.show');

// Panier
Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');

// Passer une commande
Route::post('/commande', [FrontCommandeController::class, 'store'])->name('commande.store');

// Contact / messages
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


// -------------------- ADMIN --------------------
// Toutes les routes Admin sont protégées par le middleware 'admin'
Route::prefix('admin')->middleware(['admin'])->group(function () {
    
    // Tableau de bord
    Route::get('/', [StatsController::class, 'index'])->name('admin.dashboard');

    // CRUD Produits
    Route::resource('produits', AdminProduitController::class);

    // CRUD Commandes
    Route::resource('commandes', AdminCommandeController::class);

    // CRUD Clients
    Route::resource('clients', AdminClientController::class);

    // CRUD Messages
    Route::resource('messages', AdminMessageController::class);
});