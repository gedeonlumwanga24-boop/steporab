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
                // Lier le compte existant à Google
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // 3. Créer un nouveau compte
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'password'          => null, // Pas de mot de passe pour les comptes OAuth
                    'role'              => 'client',
                    'email_verified_at' => now(), // Google garantit l'email vérifié
                ]);

                Client::create(['user_id' => $user->id]);
            }
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('compte.show'))
            ->with('success', 'Connecté avec Google. Bienvenue ' . $user->name . ' !');
    }
}
