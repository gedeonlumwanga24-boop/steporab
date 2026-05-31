<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanierItem extends Model
{
    protected $table = 'panier_items';
    
    protected $fillable = [
        'panier_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'taille',
        'couleur',
        'notes',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
    ];

    /**
     * Le panier qui contient cet article
     */
    public function panier(): BelongsTo
    {
        return $this->belongsTo(Panier::class);
    }

    /**
     * Le produit de cet article
     */
    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Calcule le sous-total de cet article
     */
    public function getSubtotalAttribute(): float
    {
        return $this->prix_unitaire * $this->quantite;
    }

    /**
     * Met à jour la quantité et le panier parent
     */
    public function updateQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            $this->delete();
        } else {
            $this->update(['quantite' => $quantity]);
        }

        $this->panier->updateTotal();
    }
}
