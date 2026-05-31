<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Category
 */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'nom'          => $this->nom,
            'slug'         => $this->slug ?? $this->nom,
            'description'  => $this->description ?? null,
            'image'        => $this->image ?? null,
            'show_in_nav'  => (bool) ($this->show_in_nav ?? true),
            'products_count' => $this->when(
                isset($this->products_count),
                $this->products_count
            ),
        ];
    }
}
