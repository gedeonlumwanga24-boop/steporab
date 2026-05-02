<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Category;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $sneakers = Category::where('name', 'Sneakers')->first();
        $running = Category::where('name', 'Running')->first();

        Produit::create([
            'nom' => 'Nike Air Force 1',
            'description' => 'Sneakers classique iconique',
            'prix' => 120000,
            'stock' => 10,
            'image' => 'images/airforce1.jpg',
            'category_id' => $sneakers->id
        ]);

        Produit::create([
            'nom' => 'Nike Dunk Low',
            'description' => 'Sneakers tendance streetwear',
            'prix' => 110000,
            'stock' => 8,
            'image' => 'images/dunklow.jpg',
            'category_id' => $sneakers->id
        ]);

        Produit::create([
            'nom' => 'Nike Air Max',
            'description' => 'Chaussure confortable avec air',
            'prix' => 130000,
            'stock' => 5,
            'image' => 'images/airmax.jpg',
            'category_id' => $running->id
        ]);
    }
}