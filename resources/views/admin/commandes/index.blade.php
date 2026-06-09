@extends('layouts.admin')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="admin-card">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="admin-card-title">Toutes les commandes</h3>
        @if($pendingCount > 0)
        <a href="{{ route('admin.commandes.paiements') }}"
           style="display: inline-flex; align-items: center; gap: 0.5rem; background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: background 0.2s;"
           onmouseover="this.style.background='#fde68a'" onmouseout="this.style.background='#fef3c7'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Paiements en attente
            <span style="background: #dc2626; color: #fff; font-size: 0.72rem; font-weight: 800; padding: 0.1rem 0.5rem; border-radius: 999px;">{{ $pendingCount }}</span>
        </a>
        @endif
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                    <tr>
                        <td><strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            {{ $commande->user->name ?? 'Utilisateur inconnu' }}<br>
                            <span style="font-size: 0.75rem; color: #6b7280;">{{ $commande->user->email ?? '' }}</span>
                        </td>
                        <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                <div>
                                    @if($commande->payment_status === 'payee')
                                        <span class="badge badge-success" style="font-size: 0.7rem;">Paiement: Payé</span>
                                    @elseif($commande->payment_status === 'en_verification')
                                        <span class="badge badge-pending" style="font-size: 0.7rem;">Paiement: En vérif</span>
                                    @elseif($commande->payment_status === 'refusee')
                                        <span class="badge badge-error" style="font-size: 0.7rem;">Paiement: Refusé</span>
                                    @else
                                        <span class="badge" style="font-size: 0.7rem; background:#f3f4f6;">Paiement: Non payé</span>
                                    @endif
                                </div>
                                @if($commande->statut !== 'en_attente')
                                <div>
                                    @if($commande->statut === 'payee')
                                        <span class="badge" style="background: #e0e7ff; color: #3730a3; font-size: 0.7rem;">Livraison: Préparation</span>
                                    @elseif($commande->statut === 'expediee')
                                        <span class="badge" style="background: #cffafe; color: #164e63; font-size: 0.7rem;">Livraison: Expédiée</span>
                                    @elseif($commande->statut === 'terminee' || $commande->statut === 'livree')
                                        <span class="badge badge-success" style="font-size: 0.7rem;">Livraison: Livrée</span>
                                    @elseif($commande->statut === 'annulee')
                                        <span class="badge badge-error" style="font-size: 0.7rem;">Livraison: Annulée</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </td>
                        <td><strong>{{ number_format($commande->total, 0, ' ', ' ') }} CDF</strong></td>
                        <td>
                            <div class="admin-action-links">
                                <a href="{{ route('admin.commandes.show', $commande->id) }}" class="btn-icon text-blue" title="Gérer">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Aucune commande trouvée.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($commandes->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border);">
            {{ $commandes->links() }}
        </div>
    @endif
</div>
@endsection
