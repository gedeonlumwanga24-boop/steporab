<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Mail\PaymentValidatedMail;
use App\Mail\PaymentRefusedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommandeController extends Controller
{
    public function index()
    {
        $commandes = Commande::with('user')->latest()->paginate(15);
        $pendingCount = Commande::where('payment_status', Commande::PAY_EN_VERIF)->count();
        return view('admin.commandes.index', compact('commandes', 'pendingCount'));
    }

    public function show(Commande $commande)
    {
        $commande->load(['user', 'user.client', 'produits']);
        return view('admin.commandes.show', compact('commande'));
    }

    /**
     * Commandes en attente de validation de paiement
     */
    public function pendingPayments()
    {
        $commandes = Commande::with('user')
            ->where('payment_status', Commande::PAY_EN_VERIF)
            ->latest()
            ->paginate(20);

        return view('admin.commandes.pending_payments', compact('commandes'));
    }

    /**
     * Valider le paiement d'une commande
     */
    public function validatePayment(Commande $commande)
    {
        $commande->update([
            'payment_status' => Commande::PAY_PAYEE,
            'statut'         => 'payee',
        ]);

        // Envoyer email de confirmation au client
        if ($commande->user && $commande->user->email) {
            $commande->load('produits', 'user');
            Mail::to($commande->user->email)
                ->send(new PaymentValidatedMail($commande));
        }

        return redirect()->back()->with('success', "Commande #{$commande->id} — paiement validé ✓ (email envoyé au client)");
    }

    /**
     * Refuser le paiement d'une commande
     */
    public function refusePayment(Commande $commande)
    {
        $commande->update([
            'payment_status' => Commande::PAY_REFUSEE,
            'statut'         => 'annulee',
        ]);

        return redirect()->back()->with('error', "Commande #{$commande->id} — paiement refusé.");
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
