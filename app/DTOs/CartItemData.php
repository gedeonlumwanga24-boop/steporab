<?php

namespace App\DTOs;

readonly class CartItemData
{
    public function __construct(
        public int $productId,
        public int $quantite,
        public ?string $taille = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            productId: (int) ($data['product_id'] ?? $data['id'] ?? 0),
            quantite: (int) ($data['quantite'] ?? 1),
            taille: $data['taille'] ?? null,
        );
    }
}
