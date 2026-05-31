<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Panier extends Model
{
    protected $fillable = ['user_id', 'session_id', 'total', 'status'];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_ABANDONED = 'abandoned';
    const STATUS_CONVERTED = 'converted';

    /**
     * Les articles du panier
     */
    public function items(): HasMany
    {
        return $this->hasMany(PanierItem::class);
    }

    /**
     * L'utilisateur propriétaire du panier
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: paniers actifs
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope: paniers abandonnés
     */
    public function scopeAbandoned(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ABANDONED);
    }

    /**
     * Obtient ou crée le panier pour l'utilisateur/session
     */
    public static function forUserOrSession(?int $userId = null, ?string $sessionId = null): self
    {
        if ($userId) {
            return static::firstOrCreate(
                ['user_id' => $userId, 'status' => self::STATUS_ACTIVE],
                ['session_id' => $sessionId, 'total' => 0]
            );
        }

        return static::firstOrCreate(
            ['session_id' => $sessionId, 'user_id' => null, 'status' => self::STATUS_ACTIVE],
            ['total' => 0]
        );
    }

    /**
     * Calcule le total du panier
     */
    public function calculateTotal(): float
    {
        return $this->items->sum(function ($item) {
            return $item->prix_unitaire * $item->quantite;
        });
    }

    /**
     * Met à jour le total
     */
    public function updateTotal(): void
    {
        $this->update(['total' => $this->calculateTotal()]);
    }

    /**
     * Compte le nombre d'articles
     */
    public function countItems(): int
    {
        return $this->items->sum('quantite');
    }

    /**
     * Vide le panier
     */
    public function empty(): void
    {
        $this->items()->delete();
        $this->update(['total' => 0]);
    }

    /**
     * Marque comme converti (commande passée)
     */
    public function convertToOrder(): void
    {
        $this->update(['status' => self::STATUS_CONVERTED]);
    }
}
