<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Category;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $sliderMax = max(80000, Produit::max('prix') ?? 80000);
        $prixMax = $request->prixMax ? intval($request->prixMax) : $sliderMax;

        $query = Produit::with('category');

        if ($prixMax > 0) {
            $query->where('prix', '<=', $prixMax);
        }

        if ($request->categorie) {
            $query->where('category_id', $request->categorie);
        }

        switch ($request->tri) {
            case 'price_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('prix', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $produits = $query->get();
        $categories = Category::orderBy('nom')->get();

        return view('produits.index', compact('produits', 'categories', 'prixMax', 'sliderMax'));
    }

    public function show($id)
    {
        $produit = Produit::with('category')->findOrFail($id);

        return view('produits.show', compact('produit'));
    }
}