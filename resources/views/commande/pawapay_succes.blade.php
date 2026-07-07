@extends('layouts.app')

@section('title', 'Paiement Confirmé — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 500px; margin: 4rem auto; padding: 0 1rem; font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);">
        
        {{-- En-tête vert de succès --}}
        <div style="background: #10b981; padding: 2.5rem 1.5rem; text-align: center; color: white;">
            <div style="width: 64px; height: 64px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem; letter-spacing: -0.01em;">Paiement Réussi</h1>
            <p style="margin: 0; font-size: 0.95rem; opacity: 0.9;">Commande #{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }} confirmée avec succès.</p>
        </div>

        {{-- Détails de la transaction --}}
        <div style="padding: 2rem 1.5rem;">
            <p style="color: #6b7280; font-size: 0.85rem; text-align: center; margin: 0 0 1.5rem;">
                Un reçu détaillé a été envoyé à <strong>{{ Auth::user()->email }}</strong>
            </p>

            <div style="border-top: 1px dashed #d1d5db; border-bottom: 1px dashed #d1d5db; padding: 1.5rem 0; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #6b7280; font-size: 0.95rem;">Méthode de paiement</span>
                    <span style="color: #111827; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                        {{-- Afficher le logo de la méthode choisie --}}
                        @if($commande->mobile_money_provider === 'VODACOM_MPESA_COD')
                            <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" style="height: 16px; object-fit: contain;">
                            M-Pesa
                        @elseif($commande->mobile_money_provider === 'AIRTEL_COD')
                            <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel" style="height: 16px; object-fit: contain;">
                            Airtel Money
                        @elseif($commande->mobile_money_provider === 'ORANGE_COD')
                            <img src="{{ asset('images/orange_money.png') }}" alt="Orange" style="height: 16px; object-fit: contain;">
                            Orange Money
                        @else
                            Mobile Money
                        @endif
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                    <span style="color: #6b7280; font-size: 0.95rem;">Numéro utilisé</span>
                    <span style="color: #111827; font-weight: 600; font-size: 0.95rem;">+{{ $commande->mobile_money_number }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280; font-size: 0.95rem;">Date et heure</span>
                    <span style="color: #111827; font-weight: 600; font-size: 0.95rem;">{{ now()->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #111827; font-size: 1.1rem; font-weight: 700;">Montant payé</span>
                <span style="color: #10b981; font-size: 1.5rem; font-weight: 800;">{{ number_format($commande->total, 0, ',', ' ') }} CDF</span>
            </div>
        </div>
    </div>

    {{-- Boutons d'action --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 2rem;">
        <a href="{{ route('compte.show') }}" style="background: #ffffff; border: 1px solid #d1d5db; color: #374151; padding: 0.85rem; border-radius: 10px; font-weight: 600; text-align: center; text-decoration: none; transition: background 0.2s; font-size: 0.95rem;">
            Mes commandes
        </a>
        <a href="{{ route('produits.index') }}" style="background: #111827; border: 1px solid #111827; color: white; padding: 0.85rem; border-radius: 10px; font-weight: 600; text-align: center; text-decoration: none; transition: background 0.2s; font-size: 0.95rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            Continuer les achats
        </a>
    </div>

</div>

<style>
@keyframes popIn {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
@endsection
