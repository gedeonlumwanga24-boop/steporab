<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Commande extends Model
{
    protected $table = 'commandes';

    protected $fillable = [
        'user_id',
        'total',
        'statut',
        'adresse'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'en attente';
    const STATUS_CONFIRMED = 'confirmée';
    const STATUS_SHIPPED = 'expédiée';
    const STATUS_DELIVERED = 'livrée';
    const STATUS_CANCELLED = 'annulée';

    /**
     * L'utilisateur qui a passé la commande
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les produits de la commande
     */
    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'commande_produit')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }

    /**
     * Scope: commandes en attente
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('statut', self::STATUS_PENDING);
    }

    /**
     * Scope: commandes confirmées
     */
    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('statut', self::STATUS_CONFIRMED);
    }

    /**
     * Scope: commandes livrées
     */
    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('statut', self::STATUS_DELIVERED);
    }

    /**
     * Scope: commandes annulées
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('statut', self::STATUS_CANCELLED);
    }

    /**
     * Scope: commandes récentes
     */
    public function scopeRecent(Builder $query, int $days = 30): Builder
    {
        return $query->whereBetween('created_at', [
            now()->subDays($days),
            now(),
        ]);
    }

    /**
     * Obtient le total en TND formaté
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 2, ',', ' ') . ' TND';
    }

    /**
     * Obtient l'étiquette du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->statut) {
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmée',
            self::STATUS_SHIPPED => 'Expédiée',
            self::STATUS_DELIVERED => 'Livrée',
            self::STATUS_CANCELLED => 'Annulée',
            default => $this->statut,
        };
    }

    /**
     * Obtient la couleur du badge pour le statut
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->statut) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_SHIPPED => 'primary',
            self::STATUS_DELIVERED => 'success',
            self::STATUS_CANCELLED => 'error',
            default => 'default',
        };
    }

    /**
     * Peut être annulée
     */
    public function canBeCancelled(): bool
    {
        return !in_array($this->statut, [self::STATUS_DELIVERED, self::STATUS_CANCELLED]);
    }
}