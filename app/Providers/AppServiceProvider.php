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

        View::composer('partials.footer', function ($view): void {
            $view->with('footerData', $this->footerData());
        });
    }

    private function footerData(): array
    {
        $productIndex = fn (array $params = []) => route('produits.index', $params);

        return [
            'selection' => [
                [
                    ['label' => 'Sneakers', 'url' => $productIndex(['categorie' => 'sneakers'])],
                    ['label' => 'Lifestyle', 'url' => $productIndex(['categorie' => 'lifestyle'])],
                    ['label' => 'Jordan', 'url' => $productIndex(['categorie' => 'jordan'])],
                    ['label' => 'Basketball', 'url' => $productIndex(['categorie' => 'basketball'])],
                    ['label' => 'Chaussures', 'url' => $productIndex(['categorie' => 'chaussures'])],
                ],
                [
                    ['label' => 'Nike', 'url' => $productIndex(['q' => 'Nike'])],
                    ['label' => 'Adidas', 'url' => $productIndex(['q' => 'Adidas'])],
                    ['label' => 'Puma', 'url' => $productIndex(['q' => 'Puma'])],
                    ['label' => 'New Balance', 'url' => $productIndex(['q' => 'New Balance'])],
                    ['label' => 'Jordan', 'url' => $productIndex(['q' => 'Jordan'])],
                ],
                [
                    ['label' => 'Vêtements', 'url' => $productIndex(['categorie' => 'vetements'])],
                    ['label' => 'Sweats & hoodies', 'url' => $productIndex(['categorie' => 'sweats-a-capuche-et-sweats'])],
                    ['label' => 'Pantalons', 'url' => $productIndex(['categorie' => 'pantalons-et-leggings'])],
                    ['label' => 'Accessoires', 'url' => $productIndex(['categorie' => 'accessoires'])],
                    ['label' => 'Nouveautés', 'url' => $productIndex()],
                ],
                [
                    ['label' => 'Sacs & sacs à dos', 'url' => $productIndex(['categorie' => 'sacs-et-sacs-a-dos'])],
                    ['label' => 'Casquettes', 'url' => $productIndex(['categorie' => 'casquettes'])],
                    ['label' => 'Chaussettes', 'url' => $productIndex(['categorie' => 'chaussettes'])],
                    ['label' => 'Meilleures ventes', 'url' => $productIndex(['tri' => 'price_desc'])],
                    ['label' => 'Promotions', 'url' => $productIndex(['tri' => 'price_asc'])],
                ],
            ],
            'columns' => [
                [
                    'title' => 'Aide',
                    'links' => [
                        ['label' => 'Assistance client', 'url' => route('contact.index')],
                        ['label' => 'Suivi de commande', 'url' => auth()->check() ? route('compte.show') : route('login')],
                        ['label' => 'Livraison & retours', 'url' => route('contact.index')],
                        ['label' => 'Modes de paiement', 'url' => route('contact.index')],
                        ['label' => 'FAQ', 'url' => route('contact.index')],
                    ],
                ],
                [
                    'title' => 'À propos',
                    'links' => [
                        ['label' => 'Qui sommes-nous ?', 'url' => route('apropos')],
                        ['label' => 'Nos valeurs', 'url' => route('apropos').'#apropos-values'],
                        ['label' => 'Nos partenaires', 'url' => route('apropos').'#apropos-brands'],
                        ['label' => 'Carrières', 'url' => route('contact.index')],
                        ['label' => 'Éthique', 'url' => route('apropos').'#apropos-values'],
                    ],
                ],
                [
                    'title' => 'Offres',
                    'links' => [
                        ['label' => 'Toute la collection', 'url' => $productIndex()],
                        ['label' => 'Nouveautés', 'url' => $productIndex()],
                        ['label' => 'Promotions', 'url' => $productIndex(['tri' => 'price_asc'])],
                        ['label' => 'Meilleures ventes', 'url' => $productIndex(['tri' => 'price_desc'])],
                    ],
                ],
            ],
            'legal' => [
                ['label' => 'Guides', 'url' => $productIndex()],
                ['label' => 'Conditions d\'utilisation', 'url' => route('contact.index')],
                ['label' => 'Politique de confidentialité', 'url' => route('contact.index')],
            ],
        ];
    }
}
