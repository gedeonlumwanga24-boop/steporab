<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'image',
        'parent_id',
        'display_order',
    ];

    // 1 catégorie → plusieurs produits
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('display_order')
            ->orderBy('nom');
    }

    public function scopeNavigation($query)
    {
        return $query->whereNull('parent_id')
            ->with('children')
            ->orderBy('display_order')
            ->orderBy('nom');
    }
}
