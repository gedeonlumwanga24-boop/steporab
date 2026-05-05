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
                    <div class="commandes-list">
                        @foreach($commandes as $commande)
                            <div class="commande-item">
                                <div class="commande-info">
                                    <h4>Commande #{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</h4>
                                    <p class="commande-date">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div class="commande-status status-{{ $commande->statut }}">
                                    {{ str_replace('_', ' ', $commande->statut) }}
                                </div>
                                <div class="commande-total">
                                    {{ number_format($commande->total, 0, ' ', ' ') }} CDF
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection
