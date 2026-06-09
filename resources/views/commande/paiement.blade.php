@extends('layouts.app')

@section('title', 'Paiement — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 580px; margin: 3rem auto; padding: 0 1rem;">

    {{-- En-tête --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 900; color: #111; margin: 0 0 0.5rem; letter-spacing: -0.02em;">Paiement Sécurisé</h1>
        <p style="color: #6b7280; margin: 0; font-size: 1.05rem;">Commande <strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
    </div>

    {{-- Bloc Montant --}}
    <div style="background: linear-gradient(135deg, #111 0%, #374151 100%); border-radius: 16px; padding: 2rem; text-align: center; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2); color: white;">
        <p style="color: #cbd5e1; font-size: 0.95rem; font-weight: 500; margin: 0 0 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Montant Total</p>
        <p style="font-size: 2.75rem; font-weight: 900; margin: 0; letter-spacing: -0.02em;">
            {{ number_format($commande->total, 0, ' ', ' ') }} <span style="font-size: 1.25rem; font-weight: 600; color: #9ca3af;">CDF</span>
        </p>
    </div>

    {{-- Instructions --}}
    <div style="margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.15rem; font-weight: 800; color: #111; margin-bottom: 1rem;">Sélectionnez votre réseau</h2>
        <p style="color: #4b5563; font-size: 0.95rem; margin-bottom: 1rem;">Veuillez effectuer le transfert du montant exact vers l'un des numéros ci-dessous.</p>
    </div>

    {{-- Options de paiement --}}
    <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2.5rem;">
        
        {{-- M-Pesa --}}
        <div class="payment-method-card" style="background: #fff; border: 1.5px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; align-items: center; gap: 1.25rem; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <div style="width: 64px; height: 64px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: #fff; border: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div style="flex: 1;">
                <p style="font-weight: 800; color: #111; margin: 0 0 0.25rem; font-size: 1.05rem;">M-Pesa</p>
                <p style="color: #4b5563; font-weight: 600; margin: 0; font-size: 1.1rem; letter-spacing: 0.05em;" id="mpesa-number">0991 234 567</p>
            </div>
            <button onclick="copyToClipboard('0991234567', this)" class="btn-copy" style="background: #f3f4f6; border: none; border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s;">
                Copier
            </button>
        </div>

        {{-- Orange Money --}}
        <div class="payment-method-card" style="background: #fff; border: 1.5px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; align-items: center; gap: 1.25rem; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <div style="width: 64px; height: 64px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: #111; border: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/orange_money.png') }}" alt="Orange Money" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="flex: 1;">
                <p style="font-weight: 800; color: #111; margin: 0 0 0.25rem; font-size: 1.05rem;">Orange Money</p>
                <p style="color: #4b5563; font-weight: 600; margin: 0; font-size: 1.1rem; letter-spacing: 0.05em;" id="orange-number">0891 234 567</p>
            </div>
            <button onclick="copyToClipboard('0891234567', this)" class="btn-copy" style="background: #f3f4f6; border: none; border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s;">
                Copier
            </button>
        </div>

        {{-- Airtel Money --}}
        <div class="payment-method-card" style="background: #fff; border: 1.5px solid #e5e7eb; border-radius: 14px; padding: 1.25rem; display: flex; align-items: center; gap: 1.25rem; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <div style="width: 64px; height: 64px; border-radius: 12px; overflow: hidden; flex-shrink: 0; background: #fff; border: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel Money" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <div style="flex: 1;">
                <p style="font-weight: 800; color: #111; margin: 0 0 0.25rem; font-size: 1.05rem;">Airtel Money</p>
                <p style="color: #4b5563; font-weight: 600; margin: 0; font-size: 1.1rem; letter-spacing: 0.05em;" id="airtel-number">0970 297 987</p>
            </div>
            <button onclick="copyToClipboard('0970297987', this)" class="btn-copy" style="background: #f3f4f6; border: none; border-radius: 10px; padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s;">
                Copier
            </button>
        </div>

    </div>

    {{-- Bouton Suivant --}}
    <div style="text-align: center;">
        <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Une fois le transfert effectué, cliquez ci-dessous pour envoyer votre preuve.</p>
        <a href="{{ route('commande.preuve', $commande->id) }}"
           style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; background: #111; color: #fff; font-weight: 800; font-size: 1.05rem; padding: 1.25rem; border-radius: 12px; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(0,0,0,0.2)';" 
           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';">
            J'ai payé — Étape suivante
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
    </div>

</div>

<style>
    .payment-method-card:hover {
        border-color: #111 !important;
        transform: translateY(-2px);
    }
    .btn-copy:hover {
        background: #e5e7eb !important;
    }
    .btn-copy:active {
        transform: scale(0.95);
    }
</style>

<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.25rem;vertical-align:text-bottom;"><polyline points="20 6 9 17 4 12"/></svg> Copié';
        btn.style.background = '#111';
        btn.style.color = '#fff';
        setTimeout(() => {
            btn.innerHTML = original;
            btn.style.background = '#f3f4f6';
            btn.style.color = '#374151';
        }, 2000);
    });
}
</script>
@endsection
