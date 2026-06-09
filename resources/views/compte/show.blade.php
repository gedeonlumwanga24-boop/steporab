@extends('layouts.app')

@section('content')
<div class="compte-page">
    <div class="compte-header">
        <div>
            <h1 class="compte-title">Mon Compte</h1>
            <p class="compte-subtitle">Bonjour, {{ $user->name }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Se déconnecter
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="auth-alert" style="background:#ecfdf5; color:#065f46; border:1px solid #d1fae5;">
            {{ session('success') }}
        </div>
    @endif

    <div class="compte-layout">
        {{-- PROFIL --}}
        <aside>
            <div class="compte-card">
                <h3 class="compte-card-title">
                    Mes Informations
                    <a href="{{ route('compte.edit') }}" class="btn-edit">Modifier</a>
                </h3>

                <div class="profil-info">
                    <span class="profil-label">Nom</span>
                    <p class="profil-value">{{ $user->name }}</p>
                </div>

                <div class="profil-info">
                    <span class="profil-label">Email</span>
                    <p class="profil-value">{{ $user->email }}</p>
                </div>

                <div class="profil-info">
                    <span class="profil-label">Téléphone</span>
                    <p class="profil-value">{{ $client->telephone ?? 'Non renseigné' }}</p>
                </div>

                <div class="profil-info">
                    <span class="profil-label">Adresse</span>
                    <p class="profil-value">{{ $client->adresse ?? 'Non renseignée' }}</p>
                </div>

                <div class="profil-info">
                    <span class="profil-label">Ville</span>
                    <p class="profil-value">{{ $client->ville ?? 'Non renseignée' }}</p>
                </div>
            </div>
        </aside>

        {{-- COMMANDES --}}
        <main>
            <div class="compte-card" style="min-height: 100%;">
                <h3 class="compte-card-title">Historique des commandes</h3>

                @if($commandes->isEmpty())
                    <div class="commandes-empty">
                        <i class="fa-solid fa-box-open" style="font-size: 2rem; margin-bottom: 1rem; color: #cbd5e1;"></i>
                        <p>Vous n'avez passé aucune commande pour le moment.</p>
                        <a href="{{ route('produits.index') }}" class="auth-link" style="text-decoration:underline;">Découvrir nos produits</a>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($commandes as $commande)
                        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; transition: box-shadow 0.2s;"
                             onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.boxShadow='none'">

                            {{-- Header commande --}}
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 1px solid #f3f4f6;">
                                <div>
                                    <p style="font-weight: 800; color: #111; margin: 0; font-size: 0.95rem;">Commande #{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    <p style="color: #9ca3af; font-size: 0.78rem; margin: 0.1rem 0 0;">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div style="text-align: right;">
                                    <p style="font-weight: 800; color: #111; margin: 0; font-size: 1rem;">{{ number_format($commande->total, 0, ' ', ' ') }} CDF</p>
                                    @php
                                        // Badge paiement
                                        $ps = $commande->payment_status;
                                        $badgePaiement = match($ps) {
                                            'payee'          => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'Payée'],
                                            'en_verification'=> ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => 'En vérif.'],
                                            'refusee'        => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Refusée'],
                                            default          => ['bg' => '#f3f4f6', 'color' => '#374151', 'label' => 'Non payé'],
                                        };
                                        
                                        // Badge livraison
                                        $st = $commande->statut;
                                        $badgeLivraison = match($st) {
                                            'en_attente' => null,
                                            'payee'      => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'label' => 'En préparation'],
                                            'expediee'   => ['bg' => '#cffafe', 'color' => '#164e63', 'label' => 'Expédiée'],
                                            'terminee'   => ['bg' => '#dcfce7', 'color' => '#166534', 'label' => 'Livrée'],
                                            'livree'     => ['bg' => '#dcfce7', 'color' => '#166534', 'label' => 'Livrée'],
                                            'annulee'    => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'Annulée'],
                                            default      => ['bg' => '#f3f4f6', 'color' => '#374151', 'label' => ucfirst($st)],
                                        };
                                    @endphp
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.25rem;">
                                        <span style="background: {{ $badgePaiement['bg'] }}; color: {{ $badgePaiement['color'] }}; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px;">
                                            Paiement : {{ $badgePaiement['label'] }}
                                        </span>
                                        @if($badgeLivraison && $st !== 'en_attente')
                                        <span style="background: {{ $badgeLivraison['bg'] }}; color: {{ $badgeLivraison['color'] }}; font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 999px;">
                                            Livraison : {{ $badgeLivraison['label'] }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Produits --}}
                            <div style="padding: 0.75rem 1.25rem;">
                                @foreach($commande->produits->take(2) as $produit)
                                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.4rem 0;">
                                    <img src="{{ $produit->image_url }}" alt="{{ $produit->nom }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #f3f4f6;">
                                    <div style="flex: 1; min-width: 0;">
                                        <p style="font-weight: 600; color: #111; margin: 0; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $produit->nom }}</p>
                                        @if($produit->pivot->taille ?? null)
                                            <p style="color: #9ca3af; margin: 0; font-size: 0.75rem;">Taille : {{ $produit->pivot->taille }}</p>
                                        @endif
                                    </div>
                                    <span style="color: #374151; font-size: 0.85rem; font-weight: 700; flex-shrink: 0;">x{{ $produit->pivot->quantite }}</span>
                                </div>
                                @endforeach
                                @if($commande->produits->count() > 2)
                                    <p style="color: #9ca3af; font-size: 0.78rem; margin: 0.25rem 0 0;">+ {{ $commande->produits->count() - 2 }} autre(s) article(s)</p>
                                @endif
                            </div>

                            {{-- Actions selon statut --}}
                            @if($commande->payment_status === 'non_paye')
                            <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #f3f4f6; background: #fffbeb;">
                                <a href="{{ route('commande.paiement', $commande->id) }}"
                                   style="display: inline-block; background: #111; color: #fff; font-weight: 700; font-size: 0.85rem; padding: 0.6rem 1.25rem; border-radius: 8px; text-decoration: none;">
                                    Payer maintenant
                                </a>
                            </div>
                            @endif

                            @if($commande->payment_status === 'refusee')
                            <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #fee2e2; background: #fff5f5;">
                                <p style="color: #991b1b; font-size: 0.82rem; margin: 0 0 0.5rem;">Votre paiement a été refusé. Veuillez soumettre une nouvelle preuve.</p>
                                <a href="{{ route('commande.preuve', $commande->id) }}"
                                   style="display: inline-block; background: #dc2626; color: #fff; font-weight: 700; font-size: 0.82rem; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;">
                                    Renvoyer la preuve
                                </a>
                            </div>
                            @endif

                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
