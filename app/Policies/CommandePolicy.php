<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Commande;

class CommandePolicy
{
    /**
     * Voir toutes les commandes (admin/manager seulement)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Voir une commande spécifique
     * L'utilisateur peut voir sa propre commande
     * L'admin/manager peut voir toutes les commandes
     */
    public function view(User $user, Commande $commande): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        return $user->id === $commande->user_id;
    }

    /**
     * Créer une commande
     * Tout utilisateur authentifié peut créer une commande
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Mettre à jour une commande
     * Seuls les admin/manager peuvent mettre à jour
     * L'utilisateur ne peut mettre à jour que sa propre commande non livrée
     */
    public function update(User $user, Commande $commande): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        // L'utilisateur ne peut modifier que sa propre commande
        // et seulement si elle n'est pas livrée/annulée
        return $user->id === $commande->user_id &&
               !in_array($commande->statut, ['livrée', 'annulée']);
    }

    /**
     * Annuler une commande
     */
    public function cancel(User $user, Commande $commande): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        return $user->id === $commande->user_id && $commande->canBeCancelled();
    }

    /**
     * Changer le statut d'une commande (admin/manager)
     */
    public function updateStatus(User $user, Commande $commande): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Supprimer une commande (admin seulement)
     */
    public function delete(User $user, Commande $commande): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Voir les détails de paiement
     */
    public function viewPaymentDetails(User $user, Commande $commande): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        return $user->id === $commande->user_id;
    }
}
