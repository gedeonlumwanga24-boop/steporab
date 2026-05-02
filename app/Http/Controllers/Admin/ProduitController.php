<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Category;

class ProduitController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('nom')->get();

        return view('admin.produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'stock' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('image')->store('produits', 'public');

        Produit::create([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'stock' => $request->input('stock', 0),
            'image' => $path,
        ]);

        return back()->with('success', 'Produit ajouté avec succès.');
    }
}