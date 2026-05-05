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
        $nouveauxMessages = Message::count();
        
        $dernieresCommandes = Commande::with('user')->latest()->take(5)->get();

        // Données pour le graphique des ventes (6 derniers mois)
        $ventesMensuelles = Commande::where('statut', '!=', 'annulee')
            ->selectRaw('SUM(total) as montant, MONTH(created_at) as mois')
            ->groupBy('mois')
            ->orderBy('mois')
            ->take(6)
            ->get();

        // Données pour le graphique des statuts
        $statsStatuts = Commande::selectRaw('statut, COUNT(*) as count')
            ->groupBy('statut')
            ->get();

        // --- NOUVEAU: Données de Trafic (Simulées pour le démo) ---
        $trafficData = [
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'visitors' => [120, 150, 180, 140, 210, 250, 190],
            'pageViews' => [450, 520, 600, 480, 700, 850, 620]
        ];

        // --- NOUVEAU: Bulletin du Marché ---
        $panierMoyen = $totalCommandes > 0 ? $chiffreAffaires / $totalCommandes : 0;
        $topCategorie = \App\Models\Category::withCount('produits')->orderBy('produits_count', 'desc')->first();
        $tauxConversion = $totalClients > 0 ? ($totalCommandes / ($totalClients * 5)) * 100 : 0; // Estimation

        return view('admin.index', compact(
            'totalProduits', 
            'totalCommandes', 
            'chiffreAffaires', 
            'totalClients', 
            'nouveauxMessages',
            'dernieresCommandes',
            'ventesMensuelles',
            'statsStatuts',
            'trafficData',
            'panierMoyen',
            'topCategorie',
            'tauxConversion'
        ));
    }
}
