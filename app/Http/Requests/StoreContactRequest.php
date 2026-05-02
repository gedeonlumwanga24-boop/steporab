<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette action.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête.
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Votre nom est requis.',
            'email.required' => 'Votre adresse email est requise.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'message.min' => 'Votre message doit contenir au moins 10 caractères.',
        ];
    }
}
