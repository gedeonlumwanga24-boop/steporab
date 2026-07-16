@extends('layouts.app')

@section('title', 'Confirmation en cours — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  .wait-wrap * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
  .wait-wrap { max-width: 480px; margin: 4rem auto; padding: 0 1rem; }

  .wait-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
  .wait-card-body { padding: 2.5rem 2rem 2rem; text-align: center; }

  /* Ring spinner */
  .ring-wrap { position: relative; width: 88px; height: 88px; margin: 0 auto 2rem; }
  .ring-svg { position: absolute; inset: 0; animation: rotate-ring 2s linear infinite; }
  .ring-track { fill: none; stroke: #e2e8f0; stroke-width: 5; }
  .ring-fill  { fill: none; stroke: #1d4ed8; stroke-width: 5; stroke-linecap: round;
                stroke-dasharray: 220; stroke-dashoffset: 60; }
  .ring-center { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
  .ring-timer { font-size: 1.1rem; font-weight: 800; color: #0f172a; line-height: 1; }
  .ring-sub   { font-size: .65rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; margin-top: .15rem; }
  @keyframes rotate-ring { to { transform: rotate(360deg); } }

  .wait-title { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 .75rem; }
  .wait-desc  { color: #475569; font-size: .9rem; line-height: 1.65; margin: 0 0 2rem; }

  /* Récap */
  .recap-box { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 1.25rem 1.5rem; text-align: left; margin-bottom: 1.75rem; }
  .recap-row { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; font-size: .875rem; }
  .recap-row:not(:last-child) { border-bottom: 1px solid #f1f5f9; }
  .recap-label { color: #64748b; }
  .recap-value { font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: .4rem; }
  .recap-logo  { height: 16px; object-fit: contain; }

  /* Statut pill */
  .status-pill { display: inline-flex; align-items: center; gap: .45rem; background: #f1f5f9; border-radius: 999px; padding: .35rem 1rem; font-size: .82rem; font-weight: 600; color: #475569; margin-bottom: 1.5rem; }
  .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #1d4ed8; animation: blink 1.4s ease-in-out infinite; flex-shrink: 0; }
  @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

  /* Annuler link */
  .cancel-link { display: inline-block; margin-top: .25rem; color: #94a3b8; font-size: .82rem; font-weight: 500; text-decoration: none; transition: color .15s; }
  .cancel-link:hover { color: #ef4444; }

  /* Footer */
  .wait-footer { border-top: 1px solid #f1f5f9; padding: .875rem 2rem; display: flex; align-items: center; justify-content: center; gap: .5rem; color: #94a3b8; font-size: .78rem; }
</style>

<div class="wait-wrap">
  <div class="wait-card">
    <div class="wait-card-body">

      {{-- Spinner avec compteur --}}
      <div class="ring-wrap">
        <svg class="ring-svg" viewBox="0 0 88 88">
          <circle class="ring-track" cx="44" cy="44" r="38"/>
          <circle class="ring-fill"  cx="44" cy="44" r="38" id="ring-arc"/>
        </svg>
        <div class="ring-center">
          <span class="ring-timer" id="timer-val">60</span>
          <span class="ring-sub">sec</span>
        </div>
      </div>

      <h1 class="wait-title">Confirmez sur votre téléphone</h1>
      <p class="wait-desc">
        Ouvrez votre application <strong>{{ $commande->payment_method_label }}</strong> et
        entrez votre code PIN pour autoriser le paiement de&nbsp;
        <strong>{{ number_format($commande->total, 0, ',', ' ') }}&nbsp;CDF</strong>.
      </p>

      {{-- Récap --}}
      <div class="recap-box">
        <div class="recap-row">
          <span class="recap-label">Commande</span>
          <span class="recap-value">#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="recap-row">
          <span class="recap-label">Opérateur</span>
          <span class="recap-value">
            @if($commande->mobile_money_provider === 'VODACOM_MPESA_COD')
              <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" class="recap-logo">
            @elseif($commande->mobile_money_provider === 'AIRTEL_COD')
              <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel" class="recap-logo">
            @elseif($commande->mobile_money_provider === 'ORANGE_COD')
              <img src="{{ asset('images/orange_money.png') }}" alt="Orange" class="recap-logo">
            @endif
            {{ $commande->payment_method_label }}
          </span>
        </div>
        <div class="recap-row">
          <span class="recap-label">Numéro débité</span>
          <span class="recap-value">+{{ $commande->mobile_money_number }}</span>
        </div>
        <div class="recap-row">
          <span class="recap-label">Montant</span>
          <span class="recap-value">{{ number_format($commande->total, 0, ',', ' ') }} CDF</span>
        </div>
      </div>

      {{-- Pill statut --}}
      <div>
        <span class="status-pill" id="status-pill">
          <span class="status-dot" id="status-dot"></span>
          <span id="status-text">En attente de votre confirmation…</span>
        </span>
      </div>

      <a href="{{ route('commande.paiement.failed', $commande->id) }}" class="cancel-link">
        Annuler la transaction
      </a>

    </div>
    <div class="wait-footer">
      <svg width="13" height="13" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
      Sécurisé par <strong style="color:#475569;margin:0 .2rem;">PawaPay</strong>
    </div>
  </div>
</div>

<script>
(function(){
  const statusUrl  = "{{ route('commande.pawapay.status', $commande->id) }}";
  const successUrl = "{{ route('commande.paiement.success', $commande->id) }}";
  const failedUrl  = "{{ route('commande.paiement.failed', $commande->id) }}";
  const TOTAL_SEC  = 60;
  const CIRCUMFERENCE = 2 * Math.PI * 38; // ≈ 238.76

  let timeLeft = TOTAL_SEC;
  const timerEl  = document.getElementById('timer-val');
  const arcEl    = document.getElementById('ring-arc');
  const dotEl    = document.getElementById('status-dot');
  const textEl   = document.getElementById('status-text');

  arcEl.style.strokeDasharray  = CIRCUMFERENCE;
  arcEl.style.strokeDashoffset = 0;

  let countdown = setInterval(() => {
    timeLeft--;
    timerEl.textContent = timeLeft;
    const progress = (TOTAL_SEC - timeLeft) / TOTAL_SEC;
    arcEl.style.strokeDashoffset = CIRCUMFERENCE * progress;
    if(timeLeft <= 0){
      clearInterval(countdown);
      clearInterval(poller);
      textEl.textContent = 'Vérification finale en cours…';
      poll();
    }
  }, 1000);

  function setSuccess(){
    clearInterval(countdown); clearInterval(poller);
    dotEl.style.background    = '#10b981';
    dotEl.style.animation     = 'none';
    textEl.textContent        = 'Paiement reçu avec succès !';
    arcEl.style.stroke        = '#10b981';
    arcEl.style.strokeDashoffset = 0;
  }
  function setFailed(){
    clearInterval(countdown); clearInterval(poller);
    dotEl.style.background    = '#ef4444';
    dotEl.style.animation     = 'none';
    textEl.textContent        = 'Transaction échouée ou annulée.';
    arcEl.style.stroke        = '#ef4444';
  }

  function poll(){
    fetch(statusUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } })
      .then(r => r.json())
      .then(data => {
        if(data.status === 'paid'){
          setSuccess();
          setTimeout(() => window.location.href = data.redirect_url || successUrl, 1000);
        } else if(data.status === 'failed'){
          setFailed();
          setTimeout(() => window.location.href = data.redirect_url || failedUrl, 1200);
        }
      })
      .catch(() => {});
  }

  setTimeout(poll, 3000);
  let poller = setInterval(poll, 5000);
})();
</script>
@endsection
