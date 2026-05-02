<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    // produit → commandes
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit', 'produit_id', 'commande_id')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}