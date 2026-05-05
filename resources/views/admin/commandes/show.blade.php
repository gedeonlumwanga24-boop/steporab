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
        
        <!-- Changer le Statut -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Statut de la Commande</h3>
            </div>
            <div style="padding: 1.5rem;">
                <form action="{{ route('admin.commandes.update', $commande->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="admin-form-group">
                        <select name="statut" class="admin-select" style="margin-bottom: 1rem;">
                            <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="payee" {{ $commande->statut === 'payee' ? 'selected' : '' }}>Payée</option>
                            <option value="expediee" {{ $commande->statut === 'expediee' ? 'selected' : '' }}>Expédiée</option>
                            <option value="terminee" {{ $commande->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
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
