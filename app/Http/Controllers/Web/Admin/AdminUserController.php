<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    /**
     * Liste tous les administrateurs.
     */
    public function index()
    {
        $admins = User::role('admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Formulaire de création d'un admin.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Enregistre un nouvel administrateur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'L\'adresse email n\'est pas valide.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'nom'      => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        // Assign the admin role via Spatie
        $user->assignRole('admin');

        return redirect()->route('admin.admins.index')
            ->with('success', "L'administrateur {$user->name} a été créé avec succès.");
    }

    /**
     * Supprime (révoque) un administrateur.
     */
    public function destroy(User $user)
    {
        // On ne peut pas se supprimer soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Retirer le rôle admin (on conserve le compte, on révoque juste les droits)
        $user->removeRole('admin');

        return redirect()->route('admin.admins.index')
            ->with('success', "Les droits administrateur de {$user->name} ont été révoqués.");
    }
}
