<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Afficher le formulaire d'envoi de newsletter
     */
    public function create()
    {
        $totalClients = User::where('role', 'client')->count();
        return view('admin.newsletter.create', compact('totalClients'));
    }

    /**
     * Envoyer la newsletter à tous les clients
     */
    public function send(Request $request)
    {
        $request->validate([
            'sujet'   => 'required|string|max:255',
            'type'    => 'required|in:solde,nouveaute,general',
            'message' => 'required|string|min:10',
        ]);

        // Récupérer tous les utilisateurs clients (non-admin)
        $clients = User::where('role', 'client')
            ->whereNotNull('email')
            ->get();

        if ($clients->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun client trouvé à qui envoyer la newsletter.');
        }

        $typeLabel = match($request->type) {
            'solde'     => '🏷️ Soldes & Promotions',
            'nouveaute' => '🆕 Nouveautés',
            default     => '📢 Annonce Générale',
        };

        $sent = 0;
        foreach ($clients as $client) {
            try {
                Mail::to($client->email)->send(new NewsletterMail(
                    sujet:     $request->sujet,
                    type:      $request->type,
                    typeLabel: $typeLabel,
                    message:   $request->message,
                    clientNom: $client->nom ?? $client->name,
                    lienBoutique: route('produits.index'),
                ));
                $sent++;
            } catch (\Exception $e) {
                // On continue même si un email échoue
                \Log::warning("Newsletter: échec d'envoi vers {$client->email} — " . $e->getMessage());
            }
        }

        return redirect()->route('admin.newsletter.create')
            ->with('success', "Newsletter envoyée avec succès à {$sent} client(s) !");
    }
}
