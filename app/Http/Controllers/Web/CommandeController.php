<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Panier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\PawaPayService;

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
            'payment_method'    => 'required|in:mpesa,orange_money,airtel_money',
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

    // ============================================================
    //  PawaPay — Paiement Mobile Money Automatisé
    // ============================================================

    /**
     * Initier un paiement PawaPay.
     * Reçoit : téléphone + opérateur choisi par le client.
     */
    public function initiatePawaPay(Request $request, Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'phone'    => 'required|string|min:9|max:15',
            'provider' => 'required|string|in:VODACOM_MPESA_COD,AIRTEL_COD,ORANGE_COD',
        ], [
            'phone.required'    => 'Veuillez entrer votre numéro de téléphone.',
            'provider.required' => 'Veuillez sélectionner votre réseau Mobile Money.',
            'provider.in'       => 'Opérateur non reconnu.',
        ]);

        // Normaliser le numéro : retirer les espaces, tirets et +
        $phone = preg_replace('/[\s\-\(\)\+]/', '', $validated['phone']);
        
        // Si ça commence par 0, on remplace par 243
        if (preg_match('/^0/', $phone)) {
            $phone = preg_replace('/^0/', '243', $phone);
        } 
        // Si c'est juste 9 chiffres (ex: 813456789), on ajoute 243
        elseif (preg_match('/^[89]\d{8}$/', $phone)) {
            $phone = '243' . $phone;
        }

        $service = new PawaPayService();
        $result  = $service->initiateDeposit($commande, $phone, $validated['provider']);

        if ($result['success']) {
            return redirect()->route('commande.pawapay.waiting', $commande->id)
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message']);
    }

    /**
     * Page d'attente de confirmation PIN par le client.
     * Un script JS va interroger checkPaymentStatus() toutes les 5 secondes.
     */
    public function waitingPayment(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }

        // Si le paiement est déjà traité, rediriger directement
        if ($commande->isPaid()) {
            return redirect()->route('commande.paiement.success', $commande->id);
        }
        if ($commande->payment_status === Commande::PAY_REFUSEE) {
            return redirect()->route('commande.paiement.failed', $commande->id);
        }

        $commande->load('produits');
        return view('commande.pawapay_attente', compact('commande'));
    }

    /**
     * Endpoint AJAX pour vérifier l'état du paiement (polling depuis la vue d'attente).
     * Retourne JSON : { status: 'paid' | 'pending' | 'failed' }
     */
    public function checkPaymentStatus(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            return response()->json(['status' => 'error'], 403);
        }

        $commande->refresh(); // Recharger depuis la DB pour avoir le statut frais

        if ($commande->isPaid()) {
            return response()->json([
                'status'      => 'paid',
                'redirect_url' => route('commande.paiement.success', $commande->id),
            ]);
        }

        if ($commande->payment_status === Commande::PAY_REFUSEE) {
            return response()->json([
                'status'      => 'failed',
                'redirect_url' => route('commande.paiement.failed', $commande->id),
            ]);
        }

        // Si le webhook n'est pas encore arrivé, on peut aussi vérifier directement chez PawaPay
        if ($commande->pawapay_deposit_id) {
            $service = new PawaPayService();
            $result  = $service->checkDepositStatus($commande->pawapay_deposit_id);

            if ($result['status'] === 'COMPLETED') {
                // Mettre à jour manuellement si le webhook n'est pas arrivé
                $commande->update([
                    'payment_status' => Commande::PAY_PAYEE,
                    'statut'         => 'confirmée',
                ]);
                return response()->json([
                    'status'       => 'paid',
                    'redirect_url' => route('commande.paiement.success', $commande->id),
                ]);
            }

            if (in_array($result['status'], ['FAILED', 'CANCELLED', 'TIMED_OUT', 'REJECTED'])) {
                $commande->update(['payment_status' => Commande::PAY_REFUSEE]);
                return response()->json([
                    'status'       => 'failed',
                    'redirect_url' => route('commande.paiement.failed', $commande->id),
                ]);
            }
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * Page de succès après confirmation du paiement.
     */
    public function paymentSuccess(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }
        $commande->load('produits');
        return view('commande.pawapay_succes', compact('commande'));
    }

    /**
     * Page d'échec de paiement (annulation, timeout, refus opérateur).
     */
    public function paymentFailed(Commande $commande)
    {
        if ($commande->user_id !== Auth::id()) {
            abort(403);
        }
        return view('commande.pawapay_echec', compact('commande'));
    }
}