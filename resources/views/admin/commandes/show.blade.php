@extends('layouts.admin')

@section('title', 'Détails Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    
    <!-- LEFT COLUMN -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Articles Commandés -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Articles Commandés</h3>
            </div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commande->produits as $produit)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        @php
                                            $imgUrl = $produit->image ? asset('storage/produits/'.$produit->image) : asset('images/2020-nike.jpg');
                                        @endphp
                                        <img src="{{ $imgUrl }}" alt="{{ $produit->nom }}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                        <strong>{{ $produit->nom }}</strong>
                                    </div>
                                </td>
                                <td>{{ number_format($produit->pivot->prix_unitaire, 0, ' ', ' ') }} CDF</td>
                                <td>x{{ $produit->pivot->quantite }}</td>
                                <td style="text-align: right; font-weight: 600;">
                                    {{ number_format($produit->pivot->prix_unitaire * $produit->pivot->quantite, 0, ' ', ' ') }} CDF
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; padding: 1.5rem; font-size: 1.1rem; font-weight: 600;">Total de la commande :</td>
                            <td style="text-align: right; padding: 1.5rem; font-size: 1.25rem; font-weight: 800;">{{ number_format($commande->total, 0, ' ', ' ') }} CDF</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    <!-- RIGHT COLUMN -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">

        <!-- Infos Paiement Mobile Money -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Paiement Mobile Money</h3>
            </div>
            <div style="padding: 1.5rem;">
                <p style="margin-top: 0; display: flex; align-items: center; justify-content: space-between;">
                    <strong>Statut actuel :</strong>
                    @if($commande->payment_status === 'payee')
                        <span class="badge badge-success">Payé</span>
                    @elseif($commande->payment_status === 'en_verification')
                        <span class="badge badge-pending">En vérification</span>
                    @elseif($commande->payment_status === 'refusee')
                        <span class="badge badge-error">Refusé / Échoué</span>
                    @else
                        <span class="badge" style="background:#f3f4f6;">Non payé</span>
                    @endif
                </p>

                @if($commande->pawapay_deposit_id)
                    <hr style="border: 0; border-top: 1px solid var(--admin-border); margin: 1rem 0;">
                    
                    <p style="margin-bottom: 0.5rem; display: flex; justify-content: space-between;">
                        <span style="color: #6b7280;">Opérateur :</span>
                        <strong>{{ $commande->payment_method_label ?? 'Mobile Money' }}</strong>
                    </p>
                    <p style="margin-bottom: 0.5rem; display: flex; justify-content: space-between;">
                        <span style="color: #6b7280;">Numéro client :</span>
                        <strong>+{{ $commande->mobile_money_number }}</strong>
                    </p>
                    <p style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #6b7280;">ID Transaction (PawaPay) :</span>
                        <span style="font-family: monospace; font-size: 0.8rem; background: #f3f4f6; padding: 0.2rem 0.5rem; border-radius: 4px;">{{ substr($commande->pawapay_deposit_id, 0, 8) }}...</span>
                    </p>

                    @if($commande->payment_status === 'en_verification')
                        <form action="{{ route('admin.commandes.sync-pawapay', $commande->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary-sm" style="width: 100%; background: #2563eb; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Forcer la vérification
                            </button>
                            <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem; text-align: center;">Vérifie le statut directement chez PawaPay</p>
                        </form>
                    @endif
                @else
                    <p style="color: #6b7280; font-size: 0.85rem; margin-top: 1rem; text-align: center;">Aucune tentative de paiement Mobile Money enregistrée pour cette commande.</p>
                @endif
            </div>
        </div>
        
        <!-- Changer le Statut -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Statut de Livraison (Fulfillment)</h3>
            </div>
            <div style="padding: 1.5rem;">
                <form action="{{ route('admin.commandes.update', $commande->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="admin-form-group">
                        <select name="statut" class="admin-select" style="margin-bottom: 1rem;">
                            <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>En attente de paiement</option>
                            <option value="payee" {{ $commande->statut === 'payee' ? 'selected' : '' }}>En préparation (Paiement validé)</option>
                            <option value="expediee" {{ $commande->statut === 'expediee' ? 'selected' : '' }}>Expédiée</option>
                            <option value="terminee" {{ $commande->statut === 'terminee' ? 'selected' : '' }}>Livrée (Terminée)</option>
                            <option value="annulee" {{ $commande->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        <button type="submit" class="btn-primary-sm" style="width: 100%;">Mettre à jour le statut</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Infos Client -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Informations Client</h3>
            </div>
            <div style="padding: 1.5rem;">
                <p style="margin-top: 0;"><strong>Nom :</strong> {{ $commande->user->name ?? 'N/A' }}</p>
                <p><strong>Email :</strong> <a href="mailto:{{ $commande->user->email ?? '' }}" style="color: #2563eb;">{{ $commande->user->email ?? 'N/A' }}</a></p>
                <hr style="border: 0; border-top: 1px solid var(--admin-border); margin: 1rem 0;">
                <h4 style="font-size: 0.875rem; color: #6b7280; text-transform: uppercase; margin-bottom: 0.5rem;">Livraison</h4>
                <p><strong>Adresse fournie lors de la commande :</strong></p>
                <p style="background: #f9fafb; padding: 1rem; border-radius: 6px; font-size: 0.875rem; border: 1px solid var(--admin-border);">
                    {!! nl2br(e($commande->adresse)) !!}
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
