<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette action.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête.
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255|unique:produits',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Obtenir les messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est requis.',
            'nom.unique' => 'Ce produit existe déjà.',
            'prix.required' => 'Le prix est requis.',
            'category_id.required' => 'Vous devez sélectionner une catégorie.',
        ];
    }
}
