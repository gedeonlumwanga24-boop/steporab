<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['admin', 'manager']) ?? false;
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', 'string', 'in:en attente,confirmée,expédiée,livrée,annulée'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in'       => 'Le statut doit être : en attente, confirmée, expédiée, livrée ou annulée.',
        ];
    }
}
