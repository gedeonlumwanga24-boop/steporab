<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Models
use App\Models\Produit;
use App\Models\Commande;
use App\Models\User;
use App\Models\Category;

// Policies
use App\Policies\ProduitPolicy;
use App\Policies\CommandePolicy;
use App\Policies\UserPolicy;
use App\Policies\CategoryPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Produit::class => ProduitPolicy::class,
        Commande::class => CommandePolicy::class,
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * Gates pour les actions complexes
         */

        // Super-admin peut tout faire
        Gate::before(function (User $user) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Admin peut tout faire (sauf ce qui est spécifiquement refusé)
        Gate::define('admin-only', function (User $user) {
            return $user->hasRole('admin');
        });

        // Manager a des droits étendus
        Gate::define('manager-only', function (User $user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        // Clients peuvent accéder à leur propre section
        Gate::define('customer-access', function (User $user) {
            return $user->hasRole('customer');
        });

        // Accès au dashboard admin
        Gate::define('access-admin-dashboard', function (User $user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        // Accès au reporting
        Gate::define('view-reports', function (User $user) {
            return $user->hasAnyRole(['admin', 'manager']);
        });

        // Gestion des utilisateurs
        Gate::define('manage-users', function (User $user) {
            return $user->hasRole('admin');
        });

        // Gestion des rôles
        Gate::define('manage-roles', function (User $user) {
            return $user->hasRole('admin');
        });

        // Gestion des permissions
        Gate::define('manage-permissions', function (User $user) {
            return $user->hasRole('admin');
        });

        // Voir les logs
        Gate::define('view-logs', function (User $user) {
            return $user->hasRole('admin');
        });

        // Voir les backups
        Gate::define('view-backups', function (User $user) {
            return $user->hasRole('admin');
        });
    }
}
