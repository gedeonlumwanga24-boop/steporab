<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Produit;

class PanierController extends Controller
{
    /**
     * Obtenir le panier courant (DB)
     */
    private function getCurrentCart()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $sessionId = session()->getId();
        return \App\Models\Panier::forUserOrSession($userId, $sessionId);
    }

    /**
     * 📦 Voir le panier
     */
    public function index()
    {
        $panier = $this->getCurrentCart();
        $cart = [];
        $total = $panier->calculateTotal();

        foreach ($panier->items as $item) {
            $cart[$item->produit_id] = [
                "id"       => $item->produit_id,
                "nom"      => $item->produit->nom,
                "prix"     => $item->prix_unitaire,
                "quantite" => $item->quantite,
                "image"    => $item->produit->image,
                "taille"   => $item->taille,
            ];
        }

        return view('panier.index', compact('cart', 'total'));
    }

    /**
     * ➕ Ajouter un produit au panier
     */
    public function ajouter(Request $request, $id)
    {
        $produit = Produit::with('category')->findOrFail($id);
        $panier = $this->getCurrentCart();

        $variantLabel = null;
        if ($request->taille) {
            $variantLabel = 'Taille : ' . $request->taille;
        }

        // Vérifier si l'article existe déjà
        $item = $panier->items()->where('produit_id', $id)->first();

        if ($item) {
            $item->updateQuantity($item->quantite + 1);
        } else {
            $panier->items()->create([
                'produit_id' => $id,
                'quantite' => 1,
                'prix_unitaire' => $produit->prix,
                'taille' => $request->taille ?? null,
            ]);
            $panier->updateTotal();
        }

        // On recharge la relation pour avoir les items à jour
        $panier->load('items');

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            $imageUrl = $produit->image
                ? asset('storage/produits/' . $produit->image)
                : asset('images/2020-nike.jpg');

            return response()->json([
                'success'    => true,
                'message'    => 'Produit ajouté au panier',
                'cart_count' => $panier->countItems(),
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
        $panier = $this->getCurrentCart();
        $item = $panier->items()->where('produit_id', $id)->first();

        if (!$item) {
            return redirect()->back();
        }

        if ($action === "plus") {
            $item->updateQuantity($item->quantite + 1);
        }

        if ($action === "moins") {
            $item->updateQuantity($item->quantite - 1);
        }

        return redirect()->back();
    }

    /**
     * ❌ Supprimer un produit du panier
     */
    public function supprimer($id)
    {
        $panier = $this->getCurrentCart();
        $item = $panier->items()->where('produit_id', $id)->first();

        if ($item) {
            $item->delete();
            $panier->updateTotal();
        }

        return redirect()->back()->with('success', 'Produit supprimé');
    }

    /**
     * 🧹 Vider tout le panier
     */
    public function vider()
    {
        $panier = $this->getCurrentCart();
        $panier->empty();

        return redirect()->back()->with('success', 'Panier vidé');
    }
}