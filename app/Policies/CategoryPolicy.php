<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    /**
     * Voir toutes les catégories (public)
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Voir une catégorie spécifique (public)
     */
    public function view(?User $user, Category $category): bool
    {
        return true;
    }

    /**
     * Créer une catégorie (admin/manager seulement)
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Mettre à jour une catégorie (admin/manager seulement)
     */
    public function update(User $user, Category $category): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Supprimer une catégorie (admin seulement)
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Restaurer une catégorie (admin seulement)
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Forcer la suppression (admin seulement)
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }
}
