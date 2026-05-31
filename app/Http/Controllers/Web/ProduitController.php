<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

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
            $category = Category::with('children')
                ->where('id', $request->categorie)
                ->orWhere('slug', $request->categorie)
                ->first();

            if ($category) {
                $categoryIds = $category->children->pluck('id')->push($category->id);
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->q) {
            $q = $request->q;
            $query->where(function($sq) use ($q) {
                $sq->where('nom', 'LIKE', "%$q%")
                   ->orWhere('description', 'LIKE', "%$q%");
            });
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
        $categories = Category::navigation()->get();

        return view('produits.index', compact('produits', 'categories', 'prixMax', 'sliderMax'));
    }

    public function show($id)
    {
        $produit = Produit::with('category')->findOrFail($id);

        return view('produits.show', compact('produit'));
    }
}
