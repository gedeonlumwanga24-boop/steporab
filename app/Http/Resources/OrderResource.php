<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transformer la ressource en tableau
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total' => (float) $this->total,
            'status' => $this->status,
            'products' => $this->products()->get()->map(function ($product) {
                return [
                    'id' => $product->id,
                    'nom' => $product->nom,
                    'prix' => (float) $product->pivot->price_unit,
                    'quantity' => (int) $product->pivot->quantity,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
