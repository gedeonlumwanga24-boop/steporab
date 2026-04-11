<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nom'];

    // 1 catégorie → plusieurs produits
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}