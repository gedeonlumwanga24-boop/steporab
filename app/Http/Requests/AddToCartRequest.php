<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Déterminer si l'utilisateur est autorisé à effectuer cette action.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Obtenir les règles de validation qui s'appliquent à la requête.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:produits,id',
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }
}
