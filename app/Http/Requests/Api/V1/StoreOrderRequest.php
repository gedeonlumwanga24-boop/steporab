<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'adresse'            => ['required', 'string', 'max:500'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:produits,id'],
            'items.*.quantite'   => ['required', 'integer', 'min:1'],
            'items.*.taille'     => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'adresse.required'            => 'L\'adresse de livraison est obligatoire.',
            'items.required'              => 'Le panier ne peut pas être vide.',
            'items.*.product_id.required' => 'Chaque article doit avoir un produit.',
            'items.*.product_id.exists'   => 'Un produit dans votre panier n\'existe plus.',
            'items.*.quantite.required'   => 'La quantité de chaque article est obligatoire.',
            'items.*.quantite.min'        => 'La quantité doit être au moins 1.',
        ];
    }
}
