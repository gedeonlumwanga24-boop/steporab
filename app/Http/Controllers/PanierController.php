<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;

class PanierController extends Controller
{
    /**
     * 📦 Voir le panier
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['prix'] * $item['quantite'];
        }

        return view('panier.index', compact('cart', 'total'));
    }

    /**
     * ➕ Ajouter un produit au panier
     */
    public function ajouter($id)
    {
        $produit = Produit::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantite']++;
        } else {
            $cart[$id] = [
                "id" => $produit->id,
                "nom" => $produit->nom,
                "prix" => $produit->prix,
                "quantite" => 1,
                "image" => $produit->image
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produit ajouté au panier');
    }

    /**
     * ➖➕ Modifier quantité
     */
    public function update($id, $action)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return redirect()->back();
        }

        if ($action === "plus") {
            $cart[$id]['quantite']++;
        }

        if ($action === "moins") {
            $cart[$id]['quantite']--;

            if ($cart[$id]['quantite'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return redirect()->back();
    }

    /**
     * ❌ Supprimer un produit du panier
     */
    public function supprimer($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produit supprimé');
    }

    /**
     * 🧹 Vider tout le panier
     */
    public function vider()
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'Panier vidé');
    }
}