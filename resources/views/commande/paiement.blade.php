@extends('layouts.app')

@section('title', 'Paiement Sécurisé — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')

{{-- Police Inter --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
  .pay-wrap * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
  .pay-wrap { max-width: 560px; margin: 3rem auto; padding: 0 1rem; }

  /* ---- Carte principale ---- */
  .pay-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); }

  /* ---- Bannière montant ---- */
  .pay-amount-bar { background: #0f172a; padding: 1.75rem 2rem; display: flex; align-items: center; justify-content: space-between; }
  .pay-amount-label { color: #94a3b8; font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
  .pay-amount-value { color: #fff; font-size: 2rem; font-weight: 800; letter-spacing: -.02em; margin-top: .2rem; }
  .pay-amount-sub { color: #475569; font-size: .75rem; margin-top: .2rem; }
  .pay-secure-pill { display: inline-flex; align-items: center; gap: .4rem; background: rgba(16,185,129,.15); color: #34d399; border-radius: 999px; padding: .3rem .75rem; font-size: .78rem; font-weight: 600; white-space: nowrap; }

  /* ---- Corps du formulaire ---- */
  .pay-body { padding: 2rem; }

  /* ---- Étape label ---- */
  .step-label { display: flex; align-items: center; gap: .6rem; margin-bottom: 1rem; }
  .step-num { width: 22px; height: 22px; border-radius: 50%; background: #0f172a; color: #fff; font-size: .78rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .step-title { font-size: .95rem; font-weight: 700; color: #0f172a; }

  /* ---- Grille opérateurs ---- */
  .provider-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: .875rem; margin-bottom: 1.75rem; }
  .provider-label { cursor: pointer; display: block; }
  .provider-label input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
  .provider-box {
    border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.1rem .5rem .9rem;
    text-align: center; background: #fff; transition: border-color .18s, box-shadow .18s, background .18s;
    position: relative; cursor: pointer; user-select: none;
  }
  .provider-box:hover { border-color: #94a3b8; background: #f8fafc; }
  .provider-box .check-dot {
    position: absolute; top: 8px; right: 8px;
    width: 18px; height: 18px; border-radius: 50%; background: #1d4ed8;
    display: none; align-items: center; justify-content: center;
  }
  .provider-box .check-dot svg { display: block; }
  .provider-box.is-selected { border-color: #1d4ed8; background: #eff6ff; box-shadow: 0 0 0 1px #1d4ed8; }
  .provider-box.is-selected .check-dot { display: flex; }
  .provider-logo { width: 52px; height: 52px; object-fit: contain; margin: 0 auto .6rem; display: block; border-radius: 8px; }
  .provider-name { font-size: .85rem; font-weight: 700; color: #0f172a; margin: 0; }

  /* ---- Champ téléphone ---- */
  .phone-field { display: flex; border: 1.5px solid #e2e8f0; border-radius: 10px; overflow: hidden; background: #fff; transition: border-color .18s, box-shadow .18s; margin-bottom: .5rem; }
  .phone-field:focus-within { border-color: #1d4ed8; box-shadow: 0 0 0 3px rgba(29,78,216,.1); }
  .phone-prefix { display: flex; align-items: center; gap: .4rem; padding: 0 .875rem; background: #f8fafc; border-right: 1.5px solid #e2e8f0; color: #374151; font-weight: 700; font-size: .9rem; white-space: nowrap; }
  .phone-prefix img { width: 22px; border-radius: 2px; flex-shrink: 0; }
  .phone-input { flex: 1; border: none; outline: none; padding: .875rem 1rem; font-size: 1.05rem; font-weight: 600; color: #0f172a; background: transparent; }
  .phone-hint { color: #64748b; font-size: .8rem; }

  /* ---- Bouton payer ---- */
  .btn-pay { width: 100%; background: #1d4ed8; color: #fff; border: none; border-radius: 10px; padding: 1rem; font-size: 1rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .6rem; transition: background .18s, transform .1s, box-shadow .18s; box-shadow: 0 4px 12px rgba(29,78,216,.25); margin-top: 1.5rem; }
  .btn-pay:hover { background: #1e40af; box-shadow: 0 6px 20px rgba(29,78,216,.3); }
  .btn-pay:active { transform: translateY(1px); }
  .btn-pay:disabled { background: #94a3b8; box-shadow: none; cursor: not-allowed; }

  /* ---- Pied de page ---- */
  .pay-footer { border-top: 1px solid #f1f5f9; padding: 1rem 2rem; display: flex; align-items: center; justify-content: center; gap: .5rem; color: #94a3b8; font-size: .8rem; }

  /* ---- Alertes ---- */
  .alert-error { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: .875rem 1.25rem; color: #b91c1c; font-weight: 500; font-size: .9rem; display: flex; align-items: flex-start; gap: .6rem; margin-bottom: 1.25rem; }

  @keyframes spin { to { transform: rotate(360deg); } }
  .spin { animation: spin 1s linear infinite; }
</style>

<div class="pay-wrap">

  @if(session('error'))
  <div class="alert-error">
    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20" style="flex-shrink:0;margin-top:.1rem"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    {{ session('error') }}
  </div>
  @endif

  <div class="pay-card">

    {{-- Bannière montant --}}
    <div class="pay-amount-bar">
      <div>
        <div class="pay-amount-label">Montant à payer</div>
        <div class="pay-amount-value">{{ number_format($commande->total, 0, ',', ' ') }}&thinsp;CDF</div>
        <div class="pay-amount-sub">Commande #{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }} · Frais inclus</div>
      </div>
      <div class="pay-secure-pill">
        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        Paiement sécurisé
      </div>
    </div>

    {{-- Corps formulaire --}}
    <form id="pay-form" class="pay-body" action="{{ route('commande.pawapay.initiate', $commande->id) }}" method="POST" novalidate>
      @csrf

      {{-- Étape 1 — Réseau --}}
      <div class="step-label">
        <span class="step-num">1</span>
        <span class="step-title">Sélectionnez votre réseau Mobile Money</span>
      </div>

      <div class="provider-grid">

        <label class="provider-label" for="p_mpesa">
          <input type="radio" id="p_mpesa" name="provider" value="VODACOM_MPESA_COD" {{ old('provider') === 'VODACOM_MPESA_COD' ? 'checked' : '' }}>
          <div class="provider-box" data-for="p_mpesa">
            <div class="check-dot"><svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
            <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" class="provider-logo">
            <p class="provider-name">M-Pesa</p>
          </div>
        </label>

        <label class="provider-label" for="p_airtel">
          <input type="radio" id="p_airtel" name="provider" value="AIRTEL_COD" {{ old('provider') === 'AIRTEL_COD' ? 'checked' : '' }}>
          <div class="provider-box" data-for="p_airtel">
            <div class="check-dot"><svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
            <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel Money" class="provider-logo">
            <p class="provider-name">Airtel Money</p>
          </div>
        </label>

        <label class="provider-label" for="p_orange">
          <input type="radio" id="p_orange" name="provider" value="ORANGE_COD" {{ old('provider') === 'ORANGE_COD' ? 'checked' : '' }}>
          <div class="provider-box" data-for="p_orange">
            <div class="check-dot"><svg width="10" height="10" fill="none" stroke="#fff" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
            <img src="{{ asset('images/orange_money.png') }}" alt="Orange Money" class="provider-logo">
            <p class="provider-name">Orange Money</p>
          </div>
        </label>

      </div>
      @error('provider')
        <p style="color:#b91c1c;font-size:.82rem;margin:-1.25rem 0 1.25rem;font-weight:500;">{{ $message }}</p>
      @enderror

      {{-- Étape 2 — Numéro --}}
      <div class="step-label">
        <span class="step-num">2</span>
        <span class="step-title">Numéro Mobile Money à débiter</span>
      </div>

      <div class="phone-field" id="phone-wrapper">
        <div class="phone-prefix">
          <img src="https://flagcdn.com/w40/cd.png" alt="CD">
          <span>+243</span>
        </div>
        <input class="phone-input" type="tel" id="phone-input" name="phone" value="{{ old('phone') }}" placeholder="81 234 5678" inputmode="numeric" maxlength="12" required>
      </div>
      <p class="phone-hint">Entrez les chiffres sans le 0 initial (ex : 81 234 5678).</p>
      @error('phone')
        <p style="color:#b91c1c;font-size:.82rem;margin:.25rem 0;font-weight:500;">{{ $message }}</p>
      @enderror

      <button type="submit" class="btn-pay" id="btn-pay">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        Confirmer le paiement
      </button>

    </form>

    {{-- Pied --}}
    <div class="pay-footer">
      <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
      Transactions traitées de manière sécurisée par <strong style="color:#475569;margin-left:.2rem;">PawaPay</strong>
    </div>
  </div>

</div>

<script>
(function(){
  const radios = document.querySelectorAll('input[name="provider"]');
  const boxes  = document.querySelectorAll('.provider-box');

  function sync(){
    radios.forEach(r => {
      const box = document.querySelector('.provider-box[data-for="'+r.id+'"]');
      if(box) box.classList.toggle('is-selected', r.checked);
    });
  }
  radios.forEach(r => { r.addEventListener('change', sync); });
  // Click on box triggers the hidden input
  boxes.forEach(box => {
    box.addEventListener('click', function(){
      const radio = document.getElementById(this.dataset.for);
      if(radio){ radio.checked = true; sync(); }
    });
  });
  sync();

  // Strip leading zero as the user types
  const phoneInput = document.getElementById('phone-input');
  phoneInput.addEventListener('input', function(){
    let v = this.value.replace(/\D/g,'');
    if(v.startsWith('0')) v = v.slice(1);
    this.value = v;
  });

  // Disable button on submit
  document.getElementById('pay-form').addEventListener('submit', function(){
    const btn = document.getElementById('btn-pay');
    btn.disabled = true;
    btn.innerHTML = '<svg class="spin" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>&nbsp;Initialisation...';
  });
})();
</script>
@endsection
