@extends('layouts.app')

@section('title', 'Paiement Confirmé — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  .suc-wrap * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
  .suc-wrap { max-width: 480px; margin: 4rem auto; padding: 0 1rem; }

  /* ---- Carte ---- */
  .suc-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.07); }

  /* ---- En-tête vert ---- */
  .suc-header { background: #0f172a; padding: 2.25rem 2rem; text-align: center; }
  .suc-icon-ring {
    width: 72px; height: 72px; border-radius: 50%;
    background: rgba(16,185,129,.15); border: 2px solid rgba(16,185,129,.35);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem; animation: pop .5s cubic-bezier(.175,.885,.32,1.275) both;
  }
  @keyframes pop { from { transform: scale(.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
  .suc-header-title { color: #f8fafc; font-size: 1.35rem; font-weight: 800; margin: 0 0 .35rem; }
  .suc-header-sub   { color: #64748b; font-size: .85rem; margin: 0; }

  /* ---- Corps ---- */
  .suc-body { padding: 2rem; }

  /* ---- Montant vedette ---- */
  .suc-amount-block { text-align: center; padding: 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; margin-bottom: 1.75rem; }
  .suc-amount-label { color: #16a34a; font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin: 0 0 .35rem; }
  .suc-amount-num   { color: #166534; font-size: 2.25rem; font-weight: 800; letter-spacing: -.02em; margin: 0; }

  /* ---- Lignes de récap ---- */
  .suc-table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
  .suc-table td { padding: .6rem 0; font-size: .875rem; border-bottom: 1px solid #f1f5f9; }
  .suc-table tr:last-child td { border-bottom: none; }
  .suc-table td:first-child { color: #64748b; }
  .suc-table td:last-child  { font-weight: 700; color: #0f172a; text-align: right; display: flex; align-items: center; justify-content: flex-end; gap: .4rem; }
  .op-logo { height: 18px; object-fit: contain; }

  /* ---- Séparateur pointillé ---- */
  .suc-sep { border: none; border-top: 1px dashed #e2e8f0; margin: 1.5rem 0; }

  /* ---- Produits ---- */
  .suc-products-title { font-size: .75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin: 0 0 .75rem; }
  .suc-product-row { display: flex; justify-content: space-between; font-size: .875rem; padding: .4rem 0; color: #374151; }
  .suc-product-row span:last-child { font-weight: 600; color: #0f172a; }

  /* ---- Boutons ---- */
  .suc-actions { display: grid; grid-template-columns: 1fr 1fr; gap: .875rem; margin-top: 1.75rem; }
  .btn-suc-primary { background: #0f172a; color: #fff; border: none; border-radius: 10px; padding: .875rem; font-size: .9rem; font-weight: 700; text-align: center; text-decoration: none; display: block; transition: background .15s; }
  .btn-suc-primary:hover { background: #1e293b; }
  .btn-suc-secondary { background: #fff; color: #374151; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: .875rem; font-size: .9rem; font-weight: 600; text-align: center; text-decoration: none; display: block; transition: border-color .15s; }
  .btn-suc-secondary:hover { border-color: #94a3b8; }

  /* ---- Pied ---- */
  .suc-footer { border-top: 1px solid #f1f5f9; padding: .875rem 2rem; text-align: center; color: #94a3b8; font-size: .78rem; display: flex; align-items: center; justify-content: center; gap: .4rem; }
</style>

<div class="suc-wrap">
  <div class="suc-card">

    {{-- En-tête sombre + icône verte --}}
    <div class="suc-header">
      <div class="suc-icon-ring">
        <svg width="36" height="36" fill="none" stroke="#10b981" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <p class="suc-header-title">Paiement confirmé</p>
      <p class="suc-header-sub">Commande&nbsp;#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="suc-body">

      {{-- Montant vedette --}}
      <div class="suc-amount-block">
        <p class="suc-amount-label">Montant payé</p>
        <p class="suc-amount-num">{{ number_format($commande->total, 0, ',', ' ') }}&thinsp;CDF</p>
      </div>

      {{-- Détails transaction --}}
      <table class="suc-table">
        <tr>
          <td>Mode de paiement</td>
          <td>
            @if($commande->mobile_money_provider === 'VODACOM_MPESA_COD')
              <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" class="op-logo">M-Pesa
            @elseif($commande->mobile_money_provider === 'AIRTEL_COD')
              <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel" class="op-logo">Airtel Money
            @elseif($commande->mobile_money_provider === 'ORANGE_COD')
              <img src="{{ asset('images/orange_money.png') }}" alt="Orange" class="op-logo">Orange Money
            @else
              Mobile Money
            @endif
          </td>
        </tr>
        <tr>
          <td>Numéro débité</td>
          <td>+{{ $commande->mobile_money_number }}</td>
        </tr>
        <tr>
          <td>Date</td>
          <td>{{ now()->format('d/m/Y à H\hi') }}</td>
        </tr>
        @if(Auth::user()->email)
        <tr>
          <td>Reçu envoyé à</td>
          <td style="font-size:.82rem;">{{ Auth::user()->email }}</td>
        </tr>
        @endif
      </table>

      <hr class="suc-sep">

      {{-- Récapitulatif des articles --}}
      <p class="suc-products-title">Articles commandés</p>
      @foreach($commande->produits as $produit)
      <div class="suc-product-row">
        <span>{{ $produit->nom }} &times; {{ $produit->pivot->quantite }}</span>
        <span>{{ number_format($produit->pivot->prix_unitaire * $produit->pivot->quantite, 0, ',', ' ') }} CDF</span>
      </div>
      @endforeach
      <div class="suc-product-row" style="font-weight:800;color:#0f172a;padding-top:.75rem;border-top:2px solid #f1f5f9;margin-top:.5rem;">
        <span>Total</span>
        <span>{{ number_format($commande->total, 0, ',', ' ') }} CDF</span>
      </div>

      {{-- Actions --}}
      <div class="suc-actions">
        <a href="{{ route('compte.show') }}" class="btn-suc-primary">Mes commandes</a>
        <a href="{{ route('produits.index') }}" class="btn-suc-secondary">Continuer les achats</a>
      </div>

    </div>

    <div class="suc-footer">
      <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
      Transaction sécurisée · PawaPay
    </div>
  </div>
</div>
@endsection
