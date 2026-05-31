<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Represents a full cart (session-based or DB-based).
 * The `$resource` here is expected to be an array structured as:
 * [
 *   'items'   => [...],
 *   'total'   => float,
 *   'count'   => int,
 * ]
 */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = collect($this->resource['items'] ?? []);

        return [
            'items'       => $items->map(fn($item) => [
                'product_id'  => $item['id'] ?? null,
                'nom'         => $item['nom'] ?? null,
                'image_url'   => $item['image'] ?? null,
                'prix'        => (float) ($item['prix'] ?? 0),
                'quantite'    => (int) ($item['quantite'] ?? 1),
                'taille'      => $item['taille'] ?? null,
                'sous_total'  => (float) ($item['prix'] ?? 0) * (int) ($item['quantite'] ?? 1),
            ]),
            'total'       => (float) ($this->resource['total'] ?? 0),
            'total_format' => number_format($this->resource['total'] ?? 0, 2, ',', ' ') . ' FCFA',
            'count'       => (int) ($this->resource['count'] ?? $items->count()),
        ];
    }
}
