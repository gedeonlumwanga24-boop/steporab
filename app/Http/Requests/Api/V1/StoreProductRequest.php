<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin', 'manager']) ?? false;
    }

    public function rules(): array
    {
        return [
            'nom'         => ['required', 'string', 'max:255'],
            'prix'        => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image'       => ['nullable', 'image', 'max:4096'],
            'galerie'     => ['nullable', 'array'],
            'galerie.*'   => ['image', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'   => 'Le nom du produit est obligatoire.',
            'prix.required'  => 'Le prix du produit est obligatoire.',
            'prix.min'       => 'Le prix ne peut pas être négatif.',
            'stock.required' => 'Le stock est obligatoire.',
            'stock.min'      => 'Le stock ne peut pas être négatif.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ];
    }
}
