<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Produit;

class ProduitPolicy
{
    /**
     * Détermine si l'utilisateur peut voir tous les produits
     * Les utilisateurs anonymes peuvent voir les produits publics
     */
    public function viewAny(?User $user): bool
    {
        return true;  // Tout le monde peut voir la liste
    }

    /**
     * Détermine si l'utilisateur peut voir un produit spécifique
     */
    public function view(?User $user, Produit $produit): bool
    {
        return true;  // Tout le monde peut voir un produit
    }

    /**
     * Détermine si l'utilisateur peut créer un produit
     * Seuls les admin/manager peuvent créer
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un produit
     * Seuls les admin/manager peuvent modifier
     */
    public function update(User $user, Produit $produit): bool
    {
        // L'admin peut tout faire
        if ($user->hasRole('admin')) {
            return true;
        }

        // Le manager ne peut modifier que ses propres produits
        // (à adapter selon votre logique métier)
        if ($user->hasRole('manager')) {
            return true;
        }

        return false;
    }

    /**
     * Détermine si l'utilisateur peut supprimer un produit
     * Seuls les admin peuvent supprimer
     */
    public function delete(User $user, Produit $produit): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut restaurer un produit (soft delete)
     */
    public function restore(User $user, Produit $produit): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut forcer la suppression d'un produit
     */
    public function forceDelete(User $user, Produit $produit): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Détermine si l'utilisateur peut gérer le stock d'un produit
     */
    public function manageStock(User $user, Produit $produit): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Détermine si l'utilisateur peut voir le prix coûtant
     */
    public function viewCost(User $user, Produit $produit): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }
}
