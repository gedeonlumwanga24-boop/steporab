<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandeProduit;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    /**
     * 🧾 Valider le panier et créer une commande
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour finaliser votre commande.');
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Votre panier est vide');
        }

        // 🧮 Calcul total
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        // 🧑 Utilisateur connecté
        $user = Auth::user();

        // 🔄 Mettre à jour le profil client pour les prochaines commandes
        if ($request->filled('name')) {
            $user->update(['name' => $request->name]);
        }
        
        $user->client()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'ville' => $request->ville
            ]
        );

        // 📝 Concaténer l'adresse complète pour la commande
        $adresseLivraison = $request->adresse . ', ' . $request->ville . ' - Tél: ' . $request->telephone;

        // 📦 Création commande
        $commande = Commande::create([
            'user_id' => $user->id,
            'total' => $total,
            'statut' => 'en_attente',
            'adresse' => $adresseLivraison
        ]);

        // 🔗 Ajouter produits dans pivot
        foreach ($cart as $item) {
            CommandeProduit::create([
                'commande_id' => $commande->id,
                'produit_id' => $item['id'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix']
            ]);
        }

        // 🧹 Vider panier
        session()->forget('cart');

        return redirect()->route('commande.succes')->with('success', 'Commande validée avec succès');
    }
}