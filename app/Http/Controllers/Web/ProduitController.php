<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $sliderMax = max(80000, (int) (Produit::max('prix') ?? 80000));
        $prixMax = $request->filled('prixMax') ? (int) $request->prixMax : $sliderMax;

        $query = Produit::with('category');

        if ($prixMax > 0) {
            $query->where('prix', '<=', $prixMax);
        }

        if ($request->filled('categorie')) {
            $category = Category::query()
                ->with('children')
                ->where(function ($q) use ($request) {
                    $q->where('slug', $request->categorie)
                        ->orWhere('id', $request->categorie);
                })
                ->first();

            if ($category) {
                $categoryIds = $category->children->pluck('id')
                    ->push($category->id)
                    ->unique()
                    ->values();

                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('nom', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");
            });
        }

        if ($request->boolean('promotion')) {
            $avgPrice = (int) (Produit::avg('prix') ?? 0);
            $threshold = max(1, (int) ($avgPrice * 0.85));
            $query->where('prix', '<=', $threshold);
        }

        match ($request->input('tri', 'recent')) {
            'price_asc' => $query->orderBy('prix', 'asc'),
            'price_desc' => $query->orderBy('prix', 'desc'),
            default => $query->latest(),
        };

        $produits = $query->get();
        $categories = Category::navigation()->get();

        return view('produits.index', compact('produits', 'categories', 'prixMax', 'sliderMax'));
    }

    public function show($id)
    {
        $produit = Produit::with('category')->findOrFail($id);

        return view('produits.show', compact('produit'));
    }
}
