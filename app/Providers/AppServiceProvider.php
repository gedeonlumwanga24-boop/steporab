<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('partials.navbar', function ($view): void {
            $fallback = collect([
                [
                    'nom' => 'Chaussures',
                    'slug' => 'chaussures',
                    'children' => collect([
                        ['nom' => 'Lifestyle', 'slug' => 'lifestyle'],
                        ['nom' => 'Jordan', 'slug' => 'jordan'],
                        ['nom' => 'Basketball', 'slug' => 'basketball'],
                        ['nom' => 'Sneakers', 'slug' => 'sneakers'],
                    ]),
                ],
                [
                    'nom' => 'Vêtements',
                    'slug' => 'vetements',
                    'children' => collect([
                        ['nom' => 'Sweats à capuche et sweats', 'slug' => 'sweats-a-capuche-et-sweats'],
                        ['nom' => 'Pantalons et leggings', 'slug' => 'pantalons-et-leggings'],
                        ['nom' => 'Survêtements', 'slug' => 'survetements'],
                        ['nom' => 'Vestes', 'slug' => 'vestes'],
                        ['nom' => 'Hauts et t-shirts', 'slug' => 'hauts-et-t-shirts'],
                        ['nom' => 'Shorts', 'slug' => 'shorts'],
                        ['nom' => "Tenues et maillots d'équipe", 'slug' => 'tenues-et-maillots-equipe'],
                    ]),
                ],
                [
                    'nom' => 'Accessoires',
                    'slug' => 'accessoires',
                    'children' => collect([
                        ['nom' => 'Chaussettes', 'slug' => 'chaussettes'],
                        ['nom' => 'Sacs et sacs à dos', 'slug' => 'sacs-et-sacs-a-dos'],
                        ['nom' => 'Casquettes', 'slug' => 'casquettes'],
                        ['nom' => 'Lunettes de soleil', 'slug' => 'lunettes-de-soleil'],
                    ]),
                ],
            ]);

            try {
                $navCategories = Schema::hasTable('categories') && Schema::hasColumn('categories', 'parent_id')
                    ? Category::navigation()->get()
                    : collect();

                $view->with('navCategories', $navCategories->isNotEmpty() ? $navCategories : $fallback);
            } catch (Throwable) {
                $view->with('navCategories', $fallback);
            }
        });
    }
}
