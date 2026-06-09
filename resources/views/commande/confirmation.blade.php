@extends('layouts.app')

@section('title', 'Confirmation — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 520px; margin: 3rem auto; padding: 0 1rem; text-align: center;">

    {{-- Icône succès --}}
    <div style="width: 72px; height: 72px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>

    <h1 style="font-size: 1.75rem; font-weight: 900; color: #111; margin: 0 0 0.5rem;">Merci !</h1>
    <p style="color: #4b5563; font-size: 1rem; margin: 0 0 2rem; line-height: 1.6;">
        Votre preuve de paiement a été envoyée.<br>
        Nous allons vérifier et vous notifier dès que le paiement sera confirmé.
    </p>

    {{-- Récapitulatif commande --}}
    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; text-align: left; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #6b7280; font-size: 0.88rem;">Date</span>
            <span style="font-weight: 600; font-size: 0.88rem; color: #111;">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #6b7280; font-size: 0.88rem;">Total</span>
            <span style="font-weight: 700; font-size: 0.88rem; color: #111;">{{ number_format($commande->total, 0, ' ', ' ') }} CDF</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="color: #6b7280; font-size: 0.88rem;">Statut</span>
            <span style="background: #fef3c7; color: #92400e; font-size: 0.8rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px;">En vérification</span>
        </div>
    </div>

    {{-- Actions --}}
    <a href="{{ route('commande.mes_commandes') }}"
       style="display: block; width: 100%; text-align: center; background: #111; color: #fff; font-weight: 800; font-size: 0.95rem; padding: 0.9rem; border-radius: 10px; text-decoration: none; margin-bottom: 0.75rem; box-sizing: border-box; transition: background 0.2s;"
       onmouseover="this.style.background='#000'" onmouseout="this.style.background='#111'">
        Voir mes commandes
    </a>
    <a href="{{ route('produits.index') }}"
       style="display: block; width: 100%; text-align: center; background: #f3f4f6; color: #111; font-weight: 700; font-size: 0.95rem; padding: 0.9rem; border-radius: 10px; text-decoration: none; box-sizing: border-box; transition: background 0.2s;"
       onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
        Continuer mes achats
    </a>

</div>
@endsection
