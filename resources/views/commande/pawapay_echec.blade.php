@extends('layouts.app')

@section('title', 'Paiement Non Abouti — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 500px; margin: 4rem auto; padding: 0 1rem; font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);">
        
        {{-- En-tête rouge d'échec --}}
        <div style="background: #ef4444; padding: 2.5rem 1.5rem; text-align: center; color: white;">
            <div style="width: 64px; height: 64px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem; letter-spacing: -0.01em;">Paiement Non Abouti</h1>
            <p style="margin: 0; font-size: 0.95rem; opacity: 0.9;">Votre paiement n'a pas pu être validé.</p>
        </div>

        {{-- Raisons et explications --}}
        <div style="padding: 2rem 1.5rem;">
            <p style="color: #4b5563; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem; text-align: center;">
                Le paiement a été annulé, refusé par l'opérateur ou le délai de confirmation a expiré. <strong>Votre commande est toujours en attente.</strong>
            </p>

            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 1.25rem;">
                <p style="font-size: 0.85rem; font-weight: 700; color: #b91c1c; margin: 0 0 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Causes fréquentes :</p>
                <ul style="color: #7f1d1d; font-size: 0.9rem; padding-left: 1.25rem; margin: 0; line-height: 1.6;">
                    <li style="margin-bottom: 0.25rem;">Annulation manuelle sur votre téléphone</li>
                    <li style="margin-bottom: 0.25rem;">Saisie de code PIN incorrecte</li>
                    <li style="margin-bottom: 0.25rem;">Solde insuffisant sur votre compte</li>
                    <li>Délai de confirmation expiré (60 secondes)</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Boutons d'action --}}
    <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin-top: 2rem;">
        <a href="{{ route('commande.paiement', $commande->id) }}" style="background: #111827; border: 1px solid #111827; color: white; padding: 1rem; border-radius: 10px; font-weight: 700; text-align: center; text-decoration: none; transition: background 0.2s; font-size: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            Réessayer le paiement
        </a>
        <a href="{{ route('compte.show') }}" style="background: #ffffff; border: 1px solid #d1d5db; color: #374151; padding: 1rem; border-radius: 10px; font-weight: 600; text-align: center; text-decoration: none; transition: background 0.2s; font-size: 0.95rem;">
            Retourner à mes commandes
        </a>
    </div>

</div>
@endsection
