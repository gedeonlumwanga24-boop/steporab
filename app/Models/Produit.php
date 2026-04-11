<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'stock',
        'image',
        'category_id'
    ];

    // produit → catégorie
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // produit → commandes (many-to-many)
    public function commandes()
    {
        return $this->belongsToMany(Commande::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}