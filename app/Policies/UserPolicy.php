<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Voir tous les utilisateurs (admin seulement)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Voir un utilisateur spécifique
     * L'utilisateur peut voir son propre profil
     * L'admin peut voir tous les profils
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * Créer un utilisateur (admin seulement)
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Mettre à jour un utilisateur
     * L'utilisateur peut modifier son propre profil
     * L'admin peut modifier tous les profils
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * Assigner des rôles (admin seulement)
     */
    public function assignRoles(User $user, User $model): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Supprimer un utilisateur (admin seulement)
     */
    public function delete(User $user, User $model): bool
    {
        // Empêcher l'auto-suppression
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasRole('admin');
    }

    /**
     * Voir les commandes d'un utilisateur
     */
    public function viewOrders(User $user, User $model): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * Modifier les paramètres d'un utilisateur (préférences, notifications, etc.)
     */
    public function updateSettings(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}
