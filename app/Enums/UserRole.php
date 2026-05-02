<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case MODERATOR = 'moderator';

    /**
     * Obtenir le label français
     */
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrateur',
            self::CLIENT => 'Client',
            self::MODERATOR => 'Modérateur',
        };
    }

    /**
     * Vérifier si le rôle est admin
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
