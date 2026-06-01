<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─────────────────────────────────────────
    // INSCRIPTION
    // ─────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('compte.show');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => 'L\'email est obligatoire.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Créer le compte utilisateur
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'client',
        ]);

        // Créer le profil client lié
        Client::create([
            'user_id' => $user->id,
        ]);

        // Connecter automatiquement
        Auth::login($user);

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

        return redirect()->route('compte.show')
            ->with('success', 'Bienvenue ' . $user->name . ' ! Votre compte a été créé.');
    }

    // ─────────────────────────────────────────
    // CONNEXION
    // ─────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('compte.show');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'L\'email est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

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

            // Si une URL était prévue (ex: panier → login → retour panier)
            return redirect()->intended(route('compte.show'))
                ->with('success', 'Connexion réussie. Bienvenue ' . Auth::user()->name . ' !');
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect.',
        ])->withInput($request->only('email'));
    }

    // ─────────────────────────────────────────
    // DÉCONNEXION
    // ─────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Vous avez été déconnecté.');
    }
}
