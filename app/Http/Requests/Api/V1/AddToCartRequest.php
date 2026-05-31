<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Guests can add to cart
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'min:1'],
            'quantite'   => ['nullable', 'integer', 'min:1', 'max:99'],
            'taille'     => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists'   => 'Ce produit n\'existe pas ou n\'est plus disponible.',
            'quantite.min'        => 'La quantité doit être au moins 1.',
            'quantite.max'        => 'La quantité ne peut pas dépasser 99.',
        ];
    }
}
