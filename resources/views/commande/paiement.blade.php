@extends('layouts.app')

@section('title', 'Paiement — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 520px; margin: 3rem auto; padding: 0 1rem;">

    {{-- Header --}}
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="width: 56px; height: 56px; background: #f0f9ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #111; margin: 0 0 0.25rem;">Paiement</h1>
        <p style="color: #6b7280; margin: 0;">Commande <strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
    </div>

    {{-- Alerte paiement manuel --}}
    <div style="background: #fffbeb; border: 1px solid #fcd34d; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: flex-start;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" style="flex-shrink:0; margin-top:2px;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <div>
            <strong style="color: #92400e; font-size: 0.9rem;">Paiement manuel</strong>
            <p style="color: #92400e; font-size: 0.85rem; margin: 0.2rem 0 0;">Veuillez envoyer le montant exact avec M-Pesa ou Orange Money.</p>
        </div>
    </div>

    {{-- M-Pesa --}}
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 44px; height: 44px; background: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <span style="color: #fff; font-weight: 900; font-size: 0.8rem;">M</span>
        </div>
        <div style="flex: 1;">
            <p style="font-weight: 700; color: #111; margin: 0; font-size: 0.95rem;">M-Pesa</p>
            <p style="color: #6b7280; margin: 0; font-size: 0.9rem;" id="mpesa-number">0991 234 567</p>
        </div>
        <button onclick="copyToClipboard('0991234567', this)" style="background: #f3f4f6; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.82rem; font-weight: 600; color: #374151; cursor: pointer; transition: all 0.2s;">
            Copier
        </button>
    </div>

    {{-- Orange Money --}}
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 44px; height: 44px; background: #f97316; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <span style="color: #fff; font-weight: 900; font-size: 0.8rem;">O</span>
        </div>
        <div style="flex: 1;">
            <p style="font-weight: 700; color: #111; margin: 0; font-size: 0.95rem;">Orange Money</p>
            <p style="color: #6b7280; margin: 0; font-size: 0.9rem;" id="orange-number">0891 234 567</p>
        </div>
        <button onclick="copyToClipboard('0891234567', this)" style="background: #f3f4f6; border: none; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.82rem; font-weight: 600; color: #374151; cursor: pointer; transition: all 0.2s;">
            Copier
        </button>
    </div>

    {{-- Montant --}}
    <div style="background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; text-align: center; margin-bottom: 1.5rem;">
        <p style="color: #6b7280; font-size: 0.85rem; margin: 0 0 0.25rem;">Montant à payer</p>
        <p style="font-size: 2rem; font-weight: 900; color: #111; margin: 0;">{{ number_format($commande->total, 0, ' ', ' ') }} <span style="font-size: 1rem; color: #6b7280;">CDF</span></p>
    </div>

    {{-- Étapes --}}
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem;">
        <p style="font-weight: 700; color: #111; margin: 0 0 0.75rem; font-size: 0.9rem;">Étapes à suivre :</p>
        <ol style="margin: 0; padding-left: 1.25rem; color: #4b5563; font-size: 0.88rem; line-height: 1.8;">
            <li>Envoyez le paiement au numéro ci-dessus</li>
            <li>Revenez sur cette page</li>
            <li>Cliquez sur <strong>"J'ai payé"</strong> et envoyez la preuve</li>
        </ol>
    </div>

    {{-- Bouton --}}
    <a href="{{ route('commande.preuve', $commande->id) }}"
       style="display: block; text-align: center; background: #111; color: #fff; font-weight: 800; font-size: 1rem; padding: 1rem; border-radius: 10px; text-decoration: none; letter-spacing: 0.02em; transition: background 0.2s;"
       onmouseover="this.style.background='#000'" onmouseout="this.style.background='#111'">
        J'ai payé — Envoyer la preuve
    </a>

</div>

<script>
function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.textContent;
        btn.textContent = '✓ Copié';
        btn.style.background = '#d1fae5';
        btn.style.color = '#065f46';
        setTimeout(() => {
            btn.textContent = original;
            btn.style.background = '#f3f4f6';
            btn.style.color = '#374151';
        }, 2000);
    });
}
</script>
@endsection
