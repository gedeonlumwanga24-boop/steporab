<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        $totalProduits = Produit::count();
        $totalCommandes = Commande::count();
        $chiffreAffaires = Commande::where('statut', '!=', 'annulee')->sum('total');
        $totalClients = User::where('role', 'client')->count();
        $nouveauxMessages = Message::count(); // Idéalement where('lu', false)->count() mais Message basique pour l'instant
        
        $dernieresCommandes = Commande::with('user')->latest()->take(5)->get();

        return view('admin.index', compact(
            'totalProduits', 
            'totalCommandes', 
            'chiffreAffaires', 
            'totalClients', 
            'nouveauxMessages',
            'dernieresCommandes'
        ));
    }
}
