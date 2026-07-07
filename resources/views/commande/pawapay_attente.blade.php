@extends('layouts.app')

@section('title', 'Confirmation en cours — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 500px; margin: 4rem auto; padding: 0 1rem; font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05); padding: 2.5rem 1.5rem; text-align: center;">
        
        {{-- Icône de chargement professionnelle --}}
        <div style="position: relative; width: 80px; height: 80px; margin: 0 auto 2rem;">
            <svg class="spinner" width="80" height="80" viewBox="0 0 50 50" style="position: absolute; top: 0; left: 0;">
                <circle cx="25" cy="25" r="20" fill="none" stroke="#e5e7eb" stroke-width="4"></circle>
                <circle cx="25" cy="25" r="20" fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round" stroke-dasharray="100" stroke-dashoffset="30"></circle>
            </svg>
            <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: 700; color: #2563eb;" id="counter-display">
                60s
            </div>
        </div>

        {{-- Instructions --}}
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0 0 0.75rem; letter-spacing: -0.01em;">Confirmez sur votre téléphone</h1>
        
        <p style="color: #4b5563; font-size: 0.95rem; line-height: 1.6; margin-bottom: 2rem;">
            Veuillez entrer votre code PIN Mobile Money sur votre téléphone pour autoriser le paiement de 
            <strong style="color: #111827;">{{ number_format($commande->total, 0, ',', ' ') }} CDF</strong>.
        </p>

        {{-- Détails du paiement en cours --}}
        <div style="background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 12px; padding: 1.25rem; text-align: left; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="color: #6b7280; font-size: 0.9rem;">Commande</span>
                <span style="font-weight: 600; color: #111827; font-size: 0.9rem;">#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="color: #6b7280; font-size: 0.9rem;">Opérateur</span>
                <span style="font-weight: 600; color: #111827; font-size: 0.9rem; display: flex; align-items: center; gap: 0.4rem;">
                    @if($commande->mobile_money_provider === 'VODACOM_MPESA_COD')
                        <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" style="height: 14px; object-fit: contain;">
                    @elseif($commande->mobile_money_provider === 'AIRTEL_COD')
                        <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel" style="height: 14px; object-fit: contain;">
                    @elseif($commande->mobile_money_provider === 'ORANGE_COD')
                        <img src="{{ asset('images/orange_money.png') }}" alt="Orange" style="height: 14px; object-fit: contain;">
                    @endif
                    {{ $commande->payment_method_label }}
                </span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280; font-size: 0.9rem;">Numéro à débiter</span>
                <span style="font-weight: 600; color: #111827; font-size: 0.9rem;">+{{ $commande->mobile_money_number }}</span>
            </div>
        </div>

        {{-- Statut texte --}}
        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #2563eb; animation: blink 1.5s infinite;"></span>
            <span id="status-msg" style="color: #374151; font-size: 0.9rem; font-weight: 500;">En attente de votre confirmation...</span>
        </div>

        {{-- Bouton d'annulation discret --}}
        <a href="{{ route('commande.paiement.failed', $commande->id) }}" style="color: #9ca3af; font-size: 0.85rem; text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'">
            Annuler la transaction
        </a>
    </div>

</div>

<style>
@keyframes spin {
    100% { transform: rotate(360deg); }
}
.spinner {
    animation: spin 2s linear infinite;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
</style>

<script>
    const statusUrl  = "{{ route('commande.pawapay.status', $commande->id) }}";
    const successUrl = "{{ route('commande.paiement.success', $commande->id) }}";
    const failedUrl  = "{{ route('commande.paiement.failed', $commande->id) }}";

    let timeLeft    = 60;
    let interval    = null;
    let pollInterval = null;

    const counterDisplay = document.getElementById('counter-display');
    const statusMsg      = document.getElementById('status-msg');

    // ---- Compte à rebours (60s) ----
    interval = setInterval(() => {
        timeLeft--;
        counterDisplay.textContent = timeLeft + 's';

        if (timeLeft <= 0) {
            clearInterval(interval);
            clearInterval(pollInterval);
            statusMsg.innerHTML = 'Temps écoulé. Vérification finale...';
            statusMsg.previousElementSibling.style.background = '#f59e0b'; // passe en orange
            checkStatus(); // Dernier check
        }
    }, 1000);

    // ---- Polling toutes les 5 secondes ----
    function checkStatus() {
        fetch(statusUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'paid') {
                clearInterval(interval);
                clearInterval(pollInterval);
                document.querySelector('.spinner').style.display = 'none';
                counterDisplay.innerHTML = '<svg width="32" height="32" fill="none" stroke="#10b981" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>';
                statusMsg.innerHTML = 'Paiement reçu avec succès !';
                statusMsg.style.color = '#10b981';
                statusMsg.previousElementSibling.style.background = '#10b981';
                setTimeout(() => window.location.href = data.redirect_url || successUrl, 1200);
            } else if (data.status === 'failed') {
                clearInterval(interval);
                clearInterval(pollInterval);
                document.querySelector('.spinner').style.display = 'none';
                counterDisplay.innerHTML = '<svg width="32" height="32" fill="none" stroke="#ef4444" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
                statusMsg.innerHTML = 'Transaction échouée ou annulée.';
                statusMsg.style.color = '#ef4444';
                statusMsg.previousElementSibling.style.background = '#ef4444';
                setTimeout(() => window.location.href = data.redirect_url || failedUrl, 1500);
            }
        })
        .catch(err => console.warn('Poll error:', err));
    }

    pollInterval = setInterval(checkStatus, 5000);
    setTimeout(checkStatus, 3000); // Premier check rapide
</script>
@endsection
