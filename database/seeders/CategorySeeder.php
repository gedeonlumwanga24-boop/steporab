<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            [
                'nom' => 'Chaussures',
                'slug' => 'chaussures',
                'children' => [
                    ['nom' => 'Lifestyle', 'slug' => 'lifestyle'],
                    ['nom' => 'Jordan', 'slug' => 'jordan'],
                    ['nom' => 'Basketball', 'slug' => 'basketball'],
                    ['nom' => 'Sneakers', 'slug' => 'sneakers'],
                ],
            ],
            [
                'nom' => 'Vêtements',
                'slug' => 'vetements',
                'children' => [
                    ['nom' => 'Sweats à capuche et sweats', 'slug' => 'sweats-a-capuche-et-sweats'],
                    ['nom' => 'Pantalons et leggings', 'slug' => 'pantalons-et-leggings'],
                    ['nom' => 'Survêtements', 'slug' => 'survetements'],
                    ['nom' => 'Vestes', 'slug' => 'vestes'],
                    ['nom' => 'Hauts et t-shirts', 'slug' => 'hauts-et-t-shirts'],
                    ['nom' => 'Shorts', 'slug' => 'shorts'],
                    ['nom' => "Tenues et maillots d'équipe", 'slug' => 'tenues-et-maillots-equipe'],
                ],
            ],
            [
                'nom' => 'Accessoires',
                'slug' => 'accessoires',
                'children' => [
                    ['nom' => 'Chaussettes', 'slug' => 'chaussettes'],
                    ['nom' => 'Sacs et sacs à dos', 'slug' => 'sacs-et-sacs-a-dos'],
                    ['nom' => 'Casquettes', 'slug' => 'casquettes'],
                    ['nom' => 'Lunettes de soleil', 'slug' => 'lunettes-de-soleil'],
                ],
            ],
        ];

        foreach ($groups as $groupIndex => $group) {
            $parent = Category::updateOrCreate(
                ['slug' => $group['slug']],
                [
                    'nom' => $group['nom'],
                    'parent_id' => null,
                    'display_order' => $groupIndex + 1,
                ]
            );

            foreach ($group['children'] as $childIndex => $child) {
                Category::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'nom' => $child['nom'],
                        'parent_id' => $parent->id,
                        'display_order' => $childIndex + 1,
                    ]
                );
            }
        }
    }
}
