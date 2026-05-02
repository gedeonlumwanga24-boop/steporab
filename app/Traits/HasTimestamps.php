<?php

namespace App\Traits;

use Carbon\Carbon;

/**
 * Trait pour ajouter des timestamps personnalisés aux modèles
 */
trait HasTimestamps
{
    /**
     * Obtenir la date de création formatée
     */
    public function getCreatedAtFormatted(): string
    {
        return $this->created_at?->format('d/m/Y H:i') ?? 'N/A';
    }

    /**
     * Obtenir la date de modification formatée
     */
    public function getUpdatedAtFormatted(): string
    {
        return $this->updated_at?->format('d/m/Y H:i') ?? 'N/A';
    }

    /**
     * Obtenir le temps écoulé depuis la création
     */
    public function getTimeAgoCreated(): string
    {
        return $this->created_at?->diffForHumans() ?? 'N/A';
    }

    /**
     * Vérifier si récemment créé (dernières 24h)
     */
    public function isRecentlyCreated(): bool
    {
        return $this->created_at?->diffInHours(Carbon::now()) <= 24;
    }
}
