@extends('layouts.app')

@section('title', 'Paiement Sécurisé — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 600px; margin: 3rem auto; padding: 0 1rem; font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- En-tête sécurisé --}}
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="display: inline-flex; align-items: center; justify-content: center; background: #f0fdf4; color: #16a34a; padding: 0.4rem 0.8rem; border-radius: 999px; font-size: 0.85rem; font-weight: 600; margin-bottom: 1rem;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" style="margin-right: 0.4rem;"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
            Paiement 100% Sécurisé
        </div>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #1f2937; margin: 0 0 0.5rem; letter-spacing: -0.01em;">Paiement Mobile Money</h1>
        <p style="color: #6b7280; margin: 0; font-size: 1rem;">Finalisation de la commande <strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></p>
    </div>

    {{-- Messages d'erreur --}}
    @if(session('error'))
        <div style="background:#fef2f2; border: 1px solid #f87171; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #b91c1c; font-weight: 500; display:flex; align-items:center; gap:0.5rem; font-size: 0.95rem;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Bloc Montant (Professionnel, style facture) --}}
    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; text-align: center; margin-bottom: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <p style="color: #6b7280; font-size: 0.85rem; font-weight: 600; margin: 0 0 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Montant Total</p>
        <p style="font-size: 2.5rem; font-weight: 800; color: #111827; margin: 0; letter-spacing: -0.02em;">
            {{ number_format($commande->total, 0, ',', ' ') }}<span style="font-size: 1.2rem; font-weight: 600; color: #6b7280; margin-left: 0.4rem;">CDF</span>
        </p>
        <p style="color: #9ca3af; font-size: 0.8rem; margin: 0.5rem 0 0;">Frais de transaction inclus</p>
    </div>

    {{-- Formulaire de paiement --}}
    <form id="pawapay-form" action="{{ route('commande.pawapay.initiate', $commande->id) }}" method="POST">
        @csrf

        {{-- ÉTAPE 1 : Choisir l'opérateur --}}
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="background: #1f2937; color: white; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.85rem; font-weight: bold; margin-right: 0.5rem;">1</span>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin: 0;">Sélectionnez votre réseau</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">

                {{-- M-Pesa --}}
                <label for="provider_mpesa" style="cursor:pointer; position: relative;">
                    <input type="radio" id="provider_mpesa" name="provider" value="VODACOM_MPESA_COD" style="display:none;" {{ old('provider') == 'VODACOM_MPESA_COD' ? 'checked' : '' }}>
                    <div class="provider-card" data-provider="VODACOM_MPESA_COD" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.25rem 0.5rem; text-align: center; transition: all 0.2s ease; background: #ffffff; cursor: pointer; position: relative; overflow: hidden;">
                        <div class="check-icon" style="position: absolute; top: 8px; right: 8px; background: #2563eb; color: white; border-radius: 50%; width: 20px; height: 20px; display: none; align-items: center; justify-content: center;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" style="width: 60px; height: 60px; object-fit: contain; margin: 0 auto 0.5rem; border-radius: 8px;">
                        <p style="font-weight: 700; font-size: 0.9rem; color: #1f2937; margin: 0;">M-Pesa</p>
                    </div>
                </label>

                {{-- Airtel Money --}}
                <label for="provider_airtel" style="cursor:pointer; position: relative;">
                    <input type="radio" id="provider_airtel" name="provider" value="AIRTEL_COD" style="display:none;" {{ old('provider') == 'AIRTEL_COD' ? 'checked' : '' }}>
                    <div class="provider-card" data-provider="AIRTEL_COD" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.25rem 0.5rem; text-align: center; transition: all 0.2s ease; background: #ffffff; cursor: pointer; position: relative; overflow: hidden;">
                        <div class="check-icon" style="position: absolute; top: 8px; right: 8px; background: #2563eb; color: white; border-radius: 50%; width: 20px; height: 20px; display: none; align-items: center; justify-content: center;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel Money" style="width: 60px; height: 60px; object-fit: contain; margin: 0 auto 0.5rem; border-radius: 8px;">
                        <p style="font-weight: 700; font-size: 0.9rem; color: #1f2937; margin: 0;">Airtel</p>
                    </div>
                </label>

                {{-- Orange Money --}}
                <label for="provider_orange" style="cursor:pointer; position: relative;">
                    <input type="radio" id="provider_orange" name="provider" value="ORANGE_COD" style="display:none;" {{ old('provider') == 'ORANGE_COD' ? 'checked' : '' }}>
                    <div class="provider-card" data-provider="ORANGE_COD" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.25rem 0.5rem; text-align: center; transition: all 0.2s ease; background: #ffffff; cursor: pointer; position: relative; overflow: hidden;">
                        <div class="check-icon" style="position: absolute; top: 8px; right: 8px; background: #2563eb; color: white; border-radius: 50%; width: 20px; height: 20px; display: none; align-items: center; justify-content: center;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <img src="{{ asset('images/orange_money.png') }}" alt="Orange Money" style="width: 60px; height: 60px; object-fit: contain; margin: 0 auto 0.5rem; border-radius: 8px;">
                        <p style="font-weight: 700; font-size: 0.9rem; color: #1f2937; margin: 0;">Orange</p>
                    </div>
                </label>

            </div>
            @error('provider')
                <p style="color:#dc2626; font-size: 0.85rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        {{-- ÉTAPE 2 : Numéro de téléphone --}}
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                <span style="background: #1f2937; color: white; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.85rem; font-weight: bold; margin-right: 0.5rem;">2</span>
                <h2 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin: 0;">Entrez le numéro de facturation</h2>
            </div>
            
            <div style="display: flex; align-items: center; border: 1.5px solid #d1d5db; border-radius: 10px; overflow: hidden; background: #ffffff; transition: border-color 0.2s, box-shadow 0.2s;" id="phone-wrapper">
                <div style="padding: 0.85rem 1rem; background: #f3f4f6; color: #4b5563; font-weight: 600; font-size: 0.95rem; border-right: 1px solid #d1d5db; display: flex; align-items: center; gap: 0.4rem;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6f/Flag_of_the_Democratic_Republic_of_the_Congo.svg" alt="RDC" style="width: 20px; border-radius: 2px;">
                    +243
                </div>
                <input
                    type="tel"
                    name="phone"
                    id="phone-input"
                    value="{{ old('phone') }}"
                    placeholder="81 234 5678"
                    inputmode="numeric"
                    pattern="[0-9\s\-]+"
                    maxlength="13"
                    style="flex: 1; padding: 0.85rem 1rem; border: none; outline: none; font-size: 1.05rem; color: #111827; background: transparent; font-weight: 600;"
                    required
                >
            </div>
            <p style="color: #6b7280; font-size: 0.85rem; margin-top: 0.5rem;">Entrez le numéro associé à votre compte Mobile Money, sans le premier 0.</p>
            @error('phone')
                <p style="color:#dc2626; font-size: 0.85rem; margin-top: 0.5rem; font-weight: 500;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bouton de paiement --}}
        <button type="submit" id="pay-btn" style="width: 100%; background: #2563eb; color: white; padding: 1.1rem; border: none; border-radius: 10px; font-size: 1.05rem; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 0.6rem; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Confirmer le paiement
        </button>
    </form>

    {{-- Note de sécurité --}}
    <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
        <p style="color: #6b7280; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem; margin: 0;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Transactions traitées de manière sécurisée par PawaPay
        </p>
    </div>

</div>

<style>
    .provider-card:hover {
        border-color: #cbd5e1 !important;
        background: #f8fafc !important;
    }
    .provider-card.selected {
        border-color: #2563eb !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 1px #2563eb;
    }
    .provider-card.selected .check-icon {
        display: flex !important;
    }
    #phone-wrapper:focus-within {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
    }
    #pay-btn:hover {
        background: #1d4ed8 !important;
    }
    #pay-btn:disabled {
        background: #9ca3af !important;
        cursor: not-allowed;
        box-shadow: none !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la sélection des opérateurs
    const radios = document.querySelectorAll('input[name="provider"]');
    const cards  = document.querySelectorAll('.provider-card');

    function updateSelection() {
        radios.forEach(radio => {
            const card = document.querySelector('[data-provider="' + radio.value + '"]');
            if (radio.checked && card) {
                card.classList.add('selected');
            } else if (card) {
                card.classList.remove('selected');
            }
        });
    }

    radios.forEach(radio => {
        radio.addEventListener('change', updateSelection);
    });
    updateSelection(); // Initialiser l'état au chargement (old input)

    document.querySelectorAll('label').forEach(label => {
        label.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                setTimeout(updateSelection, 10);
            }
        });
    });

    // Formatage basique du numéro de téléphone en direct
    const phoneInput = document.getElementById('phone-input');
    phoneInput.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, ''); // Garder uniquement les chiffres
        // Retirer le 0 initial si l'utilisateur le met
        if (value.startsWith('0') && value.length > 1) {
            value = value.substring(1);
        }
        this.value = value;
    });

    // Soumission : désactiver le bouton pour éviter les doubles clics
    document.getElementById('pawapay-form').addEventListener('submit', function() {
        const btn = document.getElementById('pay-btn');
        btn.disabled = true;
        btn.innerHTML = '<svg width="20" height="20" class="spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Initialisation en cours...';
    });
});
</script>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin 1s linear infinite; }
</style>
@endsection
