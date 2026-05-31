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
    public function ajouter(Request $request, $id)
    {
        $produit = Produit::with('category')->findOrFail($id);

        $cart = session()->get('cart', []);

        $variantLabel = null;
        if ($request->taille) {
            $variantLabel = 'Taille : ' . $request->taille;
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantite']++;
        } else {
            $cart[$id] = [
                "id"       => $produit->id,
                "nom"      => $produit->nom,
                "prix"     => $produit->prix,
                "quantite" => 1,
                "image"    => $produit->image,
                "taille"   => $request->taille ?? null,
            ];
        }

        session()->put('cart', $cart);

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            $imageUrl = $produit->image
                ? asset('storage/produits/' . $produit->image)
                : asset('images/2020-nike.jpg');

            return response()->json([
                'success'    => true,
                'message'    => 'Produit ajouté au panier',
                'cart_count' => collect($cart)->sum('quantite'),
                'product'    => [
                    'nom'      => $produit->nom,
                    'prix'     => number_format($produit->prix, 0, ' ', ' ') . ' CDF',
                    'image'    => $imageUrl,
                    'category' => $produit->category->nom ?? 'Sneakers',
                    'variant'  => $variantLabel,
                ]
            ]);
        }

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