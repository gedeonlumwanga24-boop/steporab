<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'statut',
        'adresse'
    ];

    // commande → user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // commande → produits
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }
}