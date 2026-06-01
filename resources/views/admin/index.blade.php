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

    <!-- MARKET BULLETIN & TRAFFIC -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Bulletin du Marché -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title"><i class="fa-solid fa-newspaper" style="margin-right: 8px;"></i> Bulletin du Marché</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="margin-bottom: 1.5rem;">
                    <span style="font-size: 0.8rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Panier Moyen</span>
                    <h4 style="font-size: 1.5rem; margin: 0.25rem 0;">{{ number_format($panierMoyen, 0, ' ', ' ') }} CDF</h4>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <span style="font-size: 0.8rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Top Collection</span>
                    <h4 style="font-size: 1.25rem; margin: 0.25rem 0;">{{ $topCategorie->nom ?? 'N/A' }}</h4>
                </div>
                <div>
                    <span style="font-size: 0.8rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Taux de Conversion</span>
                    <h4 style="font-size: 1.5rem; margin: 0.25rem 0; color: #10b981;">{{ number_format($tauxConversion, 1) }}%</h4>
                    <small style="color: #6b7280;">+2.4% depuis le mois dernier</small>
                </div>
            </div>
        </div>

        <!-- Graphique de Trafic -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Analyse du Trafic</h3>
            </div>
            <div style="padding: 1rem;">
                <canvas id="trafficChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="admin-charts-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Évolution des Ventes</h3>
            </div>
            <div style="padding: 1rem;">
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Répartition des Commandes</h3>
            </div>
            <div style="padding: 1rem;">
                <canvas id="ordersChart" height="250"></canvas>
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

@push('scripts')
<script>
    // Données PHP encodées avant le chargement de Chart.js
    const dashboardData = {
        sales: {
            labels: {!! json_encode($ventesMensuelles->map(fn($v) => 'Mois ' . $v->mois)) !!},
            data: {!! json_encode($ventesMensuelles->pluck('montant')) !!}
        },
        traffic: {
            labels: {!! json_encode($trafficData['labels']) !!},
            visitors: {!! json_encode($trafficData['visitors']) !!},
            pageViews: {!! json_encode($trafficData['pageViews']) !!}
        },
        orders: {
            labels: {!! json_encode($statsStatuts->pluck('statut')->map(fn($s) => ucfirst(str_replace('_', ' ', $s)))) !!},
            data: {!! json_encode($statsStatuts->pluck('count')) !!}
        }
    };

    function initCharts() {
        if (typeof Chart === 'undefined') return;

        // Évolution des Ventes
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: dashboardData.sales.labels,
                    datasets: [{
                        label: 'Ventes (CDF)',
                        data: dashboardData.sales.data,
                        backgroundColor: 'rgba(37, 99, 235, 0.6)',
                        borderColor: 'rgb(37, 99, 235)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } } }
                }
            });
        }

        // Trafic
        const trafficCtx = document.getElementById('trafficChart');
        if (trafficCtx) {
            new Chart(trafficCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dashboardData.traffic.labels,
                    datasets: [
                        {
                            label: 'Visiteurs Uniques',
                            data: dashboardData.traffic.visitors,
                            borderColor: 'rgb(37, 99, 235)',
                            backgroundColor: 'rgba(37, 99, 235, 0.08)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgb(37, 99, 235)'
                        },
                        {
                            label: 'Vues de pages',
                            data: dashboardData.traffic.pageViews,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: 'rgb(16, 185, 129)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } } }
                }
            });
        }

        // Répartition des Commandes
        const ordersCtx = document.getElementById('ordersChart');
        if (ordersCtx) {
            new Chart(ordersCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: dashboardData.orders.labels,
                    datasets: [{
                        data: dashboardData.orders.data,
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.75)',
                            'rgba(16, 185, 129, 0.75)',
                            'rgba(239, 68, 68, 0.75)',
                            'rgba(37, 99, 235, 0.75)'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 16 } }
                    },
                    cutout: '65%'
                }
            });
        }
    }

    // Charger Chart.js dynamiquement pour éviter les problèmes de CSP / race condition
    const chartScript = document.createElement('script');
    chartScript.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
    chartScript.onload = initCharts;
    chartScript.onerror = function() {
        console.warn('Impossible de charger Chart.js depuis le CDN.');
    };
    document.head.appendChild(chartScript);
</script>
@endpush
