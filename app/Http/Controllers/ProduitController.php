<?php

namespace App\Http\Controllers;

use App\Models\Produit;

class ProduitController extends Controller
{
    /**
     * 🏪 Liste des produits
     */
    public function index()
    {
        $produits = Produit::all();
        return view('produits.index', compact('produits'));
    }

    /**
     * 📄 Page détail produit (template)
     */
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        return view('produits.show', compact('produit'));
    }
}