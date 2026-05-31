<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Produit
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nom'         => $this->nom,
            'slug'        => $this->nom,                  // TODO: add slug column
            'prix'        => (float) $this->prix,
            'prix_format' => number_format($this->prix, 2, ',', ' ') . ' FCFA',
            'description' => $this->description,
            'stock'       => (int) $this->stock,
            'in_stock'    => $this->stock > 0,
            'image_url'   => $this->image_url,            // computed accessor
            'galerie'     => $this->galerie ?? [],
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'created_at'  => $this->created_at?->toISOString(),
            'updated_at'  => $this->updated_at?->toISOString(),
        ];
    }
}
