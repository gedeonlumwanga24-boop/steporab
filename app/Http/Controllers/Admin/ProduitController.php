<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        $produits = Produit::with('category')->latest()->paginate(10);
        return view('admin.produits.index', compact('produits'));
    }

    public function create()
    {
        $categories = Category::navigation()->get();
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
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'galerie.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('image')->store('produits', 'public');
        
        $galeriePaths = [];
        if ($request->hasFile('galerie')) {
            foreach ($request->file('galerie') as $image) {
                $galeriePaths[] = basename($image->store('produits', 'public'));
            }
        }

        Produit::create([
            'nom' => $request->nom,
            'prix' => $request->prix,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'stock' => $request->input('stock', 0),
            'image' => basename($path),
            'galerie' => $galeriePaths,
        ]);

        return redirect()->route('admin.produits.index')->with('success', 'Produit ajouté avec succès.');
    }

    public function edit(Produit $produit)
    {
        $categories = Category::navigation()->get();
        return view('admin.produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'galerie.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($produit->image) {
                Storage::disk('public')->delete('produits/' . $produit->image);
            }
            $path = $request->file('image')->store('produits', 'public');
            $validated['image'] = basename($path);
        }

        if ($request->hasFile('galerie')) {
            // Optionnel: supprimer l'ancienne galerie
            if ($produit->galerie) {
                foreach ($produit->galerie as $oldImage) {
                    Storage::disk('public')->delete('produits/' . $oldImage);
                }
            }
            
            $galeriePaths = [];
            foreach ($request->file('galerie') as $image) {
                $galeriePaths[] = basename($image->store('produits', 'public'));
            }
            $validated['galerie'] = $galeriePaths;
        }

        $produit->update($validated);

        return redirect()->route('admin.produits.index')->with('success', 'Produit modifié avec succès.');
    }

    public function destroy(Produit $produit)
    {
        if ($produit->image) {
            Storage::disk('public')->delete('produits/' . $produit->image);
        }
        $produit->delete();

        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé.');
    }
}
