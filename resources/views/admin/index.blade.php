@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')

    <!-- KPIs -->
    <div class="admin-kpi-grid">
        <div class="admin-kpi-card">
            <div class="admin-kpi-icon bg-blue">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="admin-kpi-info">
                <h4>Chiffre d'Affaires</h4>
                <p>{{ number_format($chiffreAffaires, 0, ' ', ' ') }} CDF</p>
            </div>
        </div>

        <div class="admin-kpi-card">
            <div class="admin-kpi-icon bg-green">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="admin-kpi-info">
                <h4>Commandes</h4>
                <p>{{ $totalCommandes }}</p>
            </div>
        </div>

        <div class="admin-kpi-card">
            <div class="admin-kpi-icon bg-purple">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="admin-kpi-info">
                <h4>Produits</h4>
                <p>{{ $totalProduits }}</p>
            </div>
        </div>

        <div class="admin-kpi-card">
            <div class="admin-kpi-icon bg-yellow">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="admin-kpi-info">
                <h4>Clients</h4>
                <p>{{ $totalClients }}</p>
            </div>
        </div>
    </div>

    <!-- Dernières commandes -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Dernières commandes</h3>
            <a href="{{ route('admin.commandes.index') }}" class="btn-primary-sm">Voir tout</a>
        </div>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieresCommandes as $commande)
                        <tr>
                            <td>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $commande->user->name ?? 'Client supprimé' }}</td>
                            <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($commande->statut === 'en_attente')
                                    <span class="badge badge-pending">En attente</span>
                                @elseif($commande->statut === 'terminee')
                                    <span class="badge badge-success">Terminée</span>
                                @elseif($commande->statut === 'annulee')
                                    <span class="badge badge-error">Annulée</span>
                                @else
                                    <span class="badge">{{ ucfirst($commande->statut) }}</span>
                                @endif
                            </td>
                            <td><strong>{{ number_format($commande->total, 0, ' ', ' ') }} CDF</strong></td>
                            <td>
                                <a href="{{ route('admin.commandes.show', $commande->id) }}" class="btn-icon text-blue" title="Voir les détails">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Aucune commande pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
