<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait pour les modèles avec soft delete
 * Ajoute automatiquement deleted_at et les méthodes associées
 */
trait HasSoftDelete
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Scope pour obtenir seulement les éléments non supprimés
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope pour obtenir seulement les éléments supprimés
     */
    public function scopeTrashed($query)
    {
        return $query->whereNotNull('deleted_at');
    }
}
