@extends('layouts.app')

@section('title', 'Paiement Non Abouti — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  .fail-wrap * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
  .fail-wrap { max-width: 480px; margin: 4rem auto; padding: 0 1rem; }

  .fail-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.07); }

  .fail-header { background: #0f172a; padding: 2.25rem 2rem; text-align: center; }
  .fail-icon-ring {
    width: 72px; height: 72px; border-radius: 50%;
    background: rgba(239,68,68,.12); border: 2px solid rgba(239,68,68,.3);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem; animation: pop .5s cubic-bezier(.175,.885,.32,1.275) both;
  }
  @keyframes pop { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  .fail-header-title { color: #f8fafc; font-size: 1.35rem; font-weight: 800; margin: 0 0 .35rem; }
  .fail-header-sub   { color: #64748b; font-size: .85rem; margin: 0; }

  .fail-body { padding: 2rem; }
  .fail-message { color: #475569; font-size: .9rem; line-height: 1.65; margin: 0 0 1.75rem; text-align: center; }

  .fail-box { background: #fff8f8; border: 1px solid #fee2e2; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 1.75rem; }
  .fail-box-title { color: #991b1b; font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin: 0 0 .75rem; }
  .fail-list { margin: 0; padding: 0 0 0 1.25rem; color: #7f1d1d; font-size: .875rem; line-height: 1.8; }

  .fail-actions { display: flex; flex-direction: column; gap: .75rem; }
  .btn-retry { background: #0f172a; color: #fff; border: none; border-radius: 10px; padding: 1rem; font-size: .95rem; font-weight: 700; text-align: center; text-decoration: none; display: block; transition: background .15s; }
  .btn-retry:hover { background: #1e293b; }
  .btn-orders { background: #fff; color: #374151; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: .9rem; font-size: .9rem; font-weight: 600; text-align: center; text-decoration: none; display: block; transition: border-color .15s; }
  .btn-orders:hover { border-color: #94a3b8; }

  .fail-footer { border-top: 1px solid #f1f5f9; padding: .875rem 2rem; text-align: center; color: #94a3b8; font-size: .78rem; }
</style>

<div class="fail-wrap">
  <div class="fail-card">

    {{-- En-tête --}}
    <div class="fail-header">
      <div class="fail-icon-ring">
        <svg width="34" height="34" fill="none" stroke="#ef4444" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </div>
      <p class="fail-header-title">Paiement non abouti</p>
      <p class="fail-header-sub">Commande&nbsp;#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="fail-body">

      <p class="fail-message">
        Le paiement Mobile Money n'a pas pu être validé. Votre commande est toujours en attente — vous pouvez réessayer sans perdre vos articles.
      </p>

      <div class="fail-box">
        <p class="fail-box-title">Causes fréquentes</p>
        <ul class="fail-list">
          <li>Annulation manuelle sur votre téléphone</li>
          <li>Code PIN incorrect ou nombre de tentatives dépassé</li>
          <li>Solde insuffisant sur votre compte Mobile Money</li>
          <li>Délai de confirmation expiré (60 secondes)</li>
        </ul>
      </div>

      <div class="fail-actions">
        <a href="{{ route('commande.paiement', $commande->id) }}" class="btn-retry">
          Réessayer le paiement
        </a>
        <a href="{{ route('compte.show') }}" class="btn-orders">
          Retourner à mes commandes
        </a>
      </div>

    </div>

    <div class="fail-footer">
      Besoin d'aide ? Contactez-nous · PawaPay
    </div>
  </div>
</div>
@endsection
