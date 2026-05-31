<?php

namespace App\DTOs;

readonly class ProductData
{
    public function __construct(
        public string $nom,
        public float $prix,
        public int $stock,
        public ?string $description = null,
        public ?string $image = null,
        public ?array $galerie = null,
        public ?int $categoryId = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            nom: $data['nom'],
            prix: (float) $data['prix'],
            stock: (int) $data['stock'],
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            galerie: $data['galerie'] ?? null,
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
        );
    }
}
