<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Commande
 */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'statut'      => $this->statut,
            'total'       => (float) $this->total,
            'total_format' => number_format($this->total, 2, ',', ' ') . ' FCFA',
            'adresse'     => $this->adresse,
            'user'        => $this->whenLoaded('user', fn() => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,
            ]),
            'items'       => $this->whenLoaded('produits', fn() =>
                $this->produits->map(fn($product) => [
                    'id'           => $product->id,
                    'nom'          => $product->nom,
                    'image_url'    => $product->image_url,
                    'quantite'     => $product->pivot->quantite,
                    'prix_unitaire' => (float) $product->pivot->prix_unitaire,
                    'sous_total'   => $product->pivot->quantite * $product->pivot->prix_unitaire,
                ])
            ),
            'items_count' => $this->produits_count ?? $this->produits?->count(),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
