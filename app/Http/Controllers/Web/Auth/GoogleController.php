<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Rediriger vers Google OAuth
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Traiter le callback Google et connecter / créer l'utilisateur
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'La connexion Google a échoué. Veuillez réessayer.');
        }

        // 1. Chercher un compte existant par google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        // 2. Si non trouvé, chercher par email (lier le compte existant)
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Lier le compte existant à Google et mettre à jour l'avatar
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar()
                ]);
            } else {
                // 3. Créer un nouveau compte
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'password'          => null, // Pas de mot de passe pour les comptes OAuth
                    'role'              => 'client',
                    'email_verified_at' => now(), // Google garantit l'email vérifié
                    'avatar'            => $googleUser->getAvatar(),
                ]);

                Client::create(['user_id' => $user->id]);
            }
        } else {
            // Utilisateur trouvé par google_id, on met à jour son avatar au cas où
            $user->update(['avatar' => $googleUser->getAvatar()]);
        }

        Auth::login($user, remember: true);

        // Fusion du panier
        $sessionId = session()->getId();
        $sessionCart = \App\Models\Panier::where('session_id', $sessionId)->whereNull('user_id')->where('status', 'active')->first();
        
        if ($sessionCart) {
            $userCart = \App\Models\Panier::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'active'],
                ['total' => 0]
            );
            
            foreach ($sessionCart->items as $item) {
                $existing = $userCart->items()->where('produit_id', $item->produit_id)->first();
                if ($existing) {
                    $existing->update(['quantite' => $existing->quantite + $item->quantite]);
                    $item->delete();
                } else {
                    $item->update(['panier_id' => $userCart->id]);
                }
            }
            $sessionCart->delete();
            $userCart->updateTotal();
        }

        return redirect()->intended(route('compte.show'))
            ->with('success', 'Connecté avec Google. Bienvenue ' . $user->name . ' !');
    }
}
