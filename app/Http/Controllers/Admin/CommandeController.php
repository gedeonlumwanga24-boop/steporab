<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::with('user')->latest()->paginate(15);
        return view('admin.commandes.index', compact('commandes'));
    }

    public function show(Commande $commande)
    {
        $commande->load(['user', 'user.client', 'produits']);
        return view('admin.commandes.show', compact('commande'));
    }

    public function update(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,payee,expediee,terminee,annulee'
        ]);

        $commande->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }
}
