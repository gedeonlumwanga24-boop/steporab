<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Commande;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'prix',
        'description',
        'image',
        'stock',
        'category_id'
    ];

    // produit → catégorie
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/2020-nike.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $imagePath = $this->image;
        
        // Check in storage/app/public/produits (main location for uploaded images)
        if (Storage::disk('public')->exists('produits/' . basename($imagePath))) {
            return asset('storage/produits/' . basename($imagePath));
        }
        
        // Check in public/storage/produits (symlink path)
        if (file_exists(public_path('storage/produits/' . basename($imagePath)))) {
            return asset('storage/produits/' . basename($imagePath));
        }

        // Check if full path exists in public/storage
        if (file_exists(public_path('storage/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        }

        // Check in resources/images (for fallback images)
        if (file_exists(resource_path('images/' . basename($imagePath)))) {
            return asset('images/' . basename($imagePath));
        }

        return asset('images/2020-nike.jpg');
    }

    // produit → commandes
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit', 'produit_id', 'commande_id')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}