<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Dashboard du compte client
     */
    public function show()
    {
        $user    = Auth::user();
        $client  = $user->client;
        $commandes = $user->commandes()->latest()->get();
        $messages = \App\Models\Message::where('email', $user->email)->latest()->get();

        // Marquer les messages répondus comme lus par le client
        \App\Models\Message::where('email', $user->email)
            ->where('status', 'répondu')
            ->where('client_read', false)
            ->update(['client_read' => true]);

        return view('compte.show', compact('user', 'client', 'commandes', 'messages'));
    }

    /**
     * Formulaire de modification du profil
     */
    public function edit()
    {
        $user   = Auth::user();
        $client = $user->client;

        return view('compte.edit', compact('user', 'client'));
    }

    /**
     * Enregistrer les modifications du profil
     */
    public function update(Request $request)
    {
        $user   = Auth::user();
        $client = $user->client;

        $request->validate([
            'name'      => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'adresse'   => 'nullable|string|max:255',
            'ville'     => 'nullable|string|max:100',
        ]);

        $user->update(['name' => $request->name]);

        $client->update([
            'telephone' => $request->telephone,
            'adresse'   => $request->adresse,
            'ville'     => $request->ville,
        ]);

        return redirect()->route('compte.show')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}
