<?php

namespace App\DTOs;

readonly class OrderData
{
    public function __construct(
        public ?int $userId,
        public float $total,
        public string $adresse,
        public string $statut = 'en attente',
        /** @var CartItemData[] */
        public array $items = [],
    ) {}

    public static function fromRequest(array $data): self
    {
        $items = [];
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = CartItemData::fromRequest($item);
            }
        }

        return new self(
            userId: isset($data['user_id']) ? (int) $data['user_id'] : null,
            total: (float) ($data['total'] ?? 0),
            adresse: $data['adresse'] ?? '',
            statut: $data['statut'] ?? 'en attente',
            items: $items,
        );
    }
}
