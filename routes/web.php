<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ContactController;

// Admin Controllers
use App\Http\Controllers\Admin\ProduitController as AdminProduitController;
use App\Http\Controllers\Admin\CommandeController as AdminCommandeController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\StatsController;


// -------------------- FRONT-END --------------------

// Accueil
Route::get('/', function () {
    return view('welcome');
});

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

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


// -------------------- ADMIN --------------------

Route::prefix('admin')->middleware(['admin'])->group(function () {

    // Dashboard
    Route::get('/', [StatsController::class, 'index'])->name('admin.dashboard');

    // Produits (inclut create + store automatiquement)
    Route::resource('produits', AdminProduitController::class);

    // Commandes
    Route::resource('commandes', AdminCommandeController::class);

    // Clients
    Route::resource('clients', AdminClientController::class);

    // Messages
    Route::resource('messages', AdminMessageController::class);
});