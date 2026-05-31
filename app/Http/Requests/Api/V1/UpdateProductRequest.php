<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin', 'manager']) ?? false;
    }

    public function rules(): array
    {
        return [
            'nom'         => ['sometimes', 'required', 'string', 'max:255'],
            'prix'        => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock'       => ['sometimes', 'required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image'       => ['nullable', 'image', 'max:4096'],
            'galerie'     => ['nullable', 'array'],
            'galerie.*'   => ['image', 'max:4096'],
        ];
    }
}
