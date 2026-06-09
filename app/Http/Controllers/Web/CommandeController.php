<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Panier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommandeController extends Controller
{
    /**
     * Valider le panier et créer la commande → redirect vers page paiement
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour finaliser votre commande.');
        }

        // Le panier est stocké en base (modèle Panier + PanierItem)
        $panier = Panier::forUserOrSession(Auth::id(), session()->getId());
        $panier->load('items.produit');

        if ($panier->items->isEmpty()) {
            return redirect()->back()->with('error', 'Votre panier est vide.');
        }

        $total = $panier->calculateTotal();

        $user = Auth::user();

        if ($request->filled('name')) {
            $user->update(['name' => $request->name]);
        }

        $user->client()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'telephone' => $request->telephone,
                'adresse'   => $request->adresse,
                'ville'     => $request->ville
            ]
        );

        $adresseLivraison = ($request->adresse ?? '') . ', ' . ($request->ville ?? '') . ' - Tél: ' . ($request->telephone ?? '');

        $commande = Commande::create([
            'user_id'        => $user->id,
            'total'          => $total,
            'statut'         => 'en_attente',
            'adresse'        => $adresseLivraison,
            'payment_status' => Commande::PAY_NON_PAYE,
        ]);

        foreach ($panier->items as $item) {
            CommandeProduit::create([
                'commande_id'   => $commande->id,
                'produit_id'    => $item->produit_id,
                'quantite'      => $item->quantite,
                'prix_unitaire' => $item->prix_unitaire,
            ]);
        }

        // Marquer le panier comme converti et le vider
        $panier->convertToOrder();
        $panier->empty();

        // Vider aussi l'éventuelle clé session 'cart' (ancienne logique)
        session()->forget('cart');

        return redirect()->route('commande.paiement', $commande->id);
    }

    /**
     * Afficher la page de paiement manuel (M-Pesa / Orange Money)
     */
    public function showPayment(Commande $commande)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        return view('commande.paiement', compact('commande'));
    }

    /**
     * Afficher le formulaire de preuve de paiement
     */
    public function showProof(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        return view('commande.preuve', compact('commande'));
    }

    /**
     * Traiter la soumission de la preuve de paiement
     */
    public function submitProof(Request $request, Commande $commande)
    {
        // Vérifie que la commande appartient au user connecté
        if ((int)$commande->user_id !== (int)Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method'    => 'required|in:mpesa,orange_money',
            'payment_phone'     => 'required|string|max:20',
            'payment_reference' => 'required|string|max:100',
            'payment_proof'     => 'required|file|max:10240',
        ], [
            'payment_method.required'    => 'Veuillez choisir le mode de paiement.',
            'payment_method.in'          => 'Mode de paiement invalide.',
            'payment_phone.required'     => 'Le numéro utilisé est obligatoire.',
            'payment_reference.required' => 'La référence de transaction est obligatoire.',
            'payment_proof.required'     => 'La capture d\'écran est obligatoire.',
            'payment_proof.file'         => 'Le fichier est invalide.',
            'payment_proof.max'          => 'La capture ne doit pas dépasser 10 Mo.',
        ]);

        try {
            // S'assurer que le dossier existe
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            if (!$disk->exists('preuves')) {
                $disk->makeDirectory('preuves');
            }

            // Stocker le fichier
            $file      = $request->file('payment_proof');
            $filename  = time() . '_' . $commande->id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('preuves', $filename, 'public');

            $commande->update([
                'payment_method'    => $validated['payment_method'],
                'payment_phone'     => $validated['payment_phone'],
                'payment_reference' => $validated['payment_reference'],
                'payment_proof'     => $filename,
                'payment_status'    => Commande::PAY_EN_VERIF,
            ]);

            return redirect()->route('commande.confirmation', $commande->id);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('submitProof error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur s\'est produite lors de l\'envoi. Veuillez réessayer.');
        }
    }

    /**
     * Page de confirmation après soumission de la preuve
     */
    public function confirmation(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        $commande->load('produits');

        return view('commande.confirmation', compact('commande'));
    }

    /**
     * Liste des commandes (maintenant gérées dans le ProfileController via compte.show)
     */
}