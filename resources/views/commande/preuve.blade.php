@extends('layouts.app')

@section('title', 'Preuve de paiement — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 520px; margin: 3rem auto; padding: 0 1rem;">

    {{-- Header --}}
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
        <a href="{{ route('commande.paiement', $commande->id) }}" style="color: #6b7280; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Retour
        </a>
    </div>

    <h1 style="font-size: 1.5rem; font-weight: 800; color: #111; margin: 0 0 0.25rem;">Preuve de paiement</h1>
    <p style="color: #6b7280; margin: 0 0 2rem;">Commande <strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></p>

    {{-- Info box --}}
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: flex-start;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" style="flex-shrink:0; margin-top:1px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <p style="color: #166534; font-size: 0.88rem; margin: 0;">Veuillez fournir les informations de votre paiement.</p>
    </div>

    {{-- Flash error --}}
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #991b1b; font-size: 0.88rem;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;">
            <ul style="margin: 0; padding-left: 1.25rem; color: #991b1b; font-size: 0.88rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('commande.submitProof', $commande->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Mode de paiement --}}
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 700; color: #111; font-size: 0.9rem; margin-bottom: 0.75rem;">Mode de paiement utilisé</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                <label style="cursor: pointer;">
                    <input type="radio" name="payment_method" value="mpesa" {{ old('payment_method') == 'mpesa' ? 'checked' : '' }} style="display:none;" class="method-radio">
                    <div class="method-card" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 1rem; text-align: center; transition: all 0.2s; background: #fff;">
                        <div style="width: 36px; height: 36px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                            <span style="color:#fff; font-weight:900; font-size:0.85rem;">M</span>
                        </div>
                        <span style="font-weight: 700; font-size: 0.9rem; color: #111;">M-Pesa</span>
                    </div>
                </label>
                <label style="cursor: pointer;">
                    <input type="radio" name="payment_method" value="orange_money" {{ old('payment_method') == 'orange_money' ? 'checked' : '' }} style="display:none;" class="method-radio">
                    <div class="method-card" style="border: 2px solid #e5e7eb; border-radius: 10px; padding: 1rem; text-align: center; transition: all 0.2s; background: #fff;">
                        <div style="width: 36px; height: 36px; background: #f97316; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem;">
                            <span style="color:#fff; font-weight:900; font-size:0.85rem;">O</span>
                        </div>
                        <span style="font-weight: 700; font-size: 0.9rem; color: #111;">Orange Money</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- Numéro utilisé --}}
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 700; color: #111; font-size: 0.9rem; margin-bottom: 0.5rem;">Numéro utilisé *</label>
            <input type="text" name="payment_phone" value="{{ old('payment_phone') }}" placeholder="0991 234 567"
                   style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.95rem; color: #111; box-sizing: border-box; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#111'" onblur="this.style.borderColor='#e5e7eb'">
        </div>

        {{-- Référence / N° de transaction --}}
        <div style="margin-bottom: 1.25rem;">
            <label style="display: block; font-weight: 700; color: #111; font-size: 0.9rem; margin-bottom: 0.5rem;">Référence / N° de transaction *</label>
            <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Ex: 123456789"
                   style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 0.95rem; color: #111; box-sizing: border-box; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#111'" onblur="this.style.borderColor='#e5e7eb'">
        </div>

        {{-- Capture d'écran --}}
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 700; color: #111; font-size: 0.9rem; margin-bottom: 0.5rem;">Capture d'écran du paiement *</label>
            <div id="dropzone" style="border: 2px dashed #e5e7eb; border-radius: 10px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #fafafa;"
                 onclick="document.getElementById('proofFile').click()"
                 ondragover="event.preventDefault(); this.style.borderColor='#111'"
                 ondragleave="this.style.borderColor='#e5e7eb'">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin-bottom:0.5rem;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <p style="color: #6b7280; font-size: 0.88rem; margin: 0;" id="dropzoneText">Cliquez ou glissez une image ici</p>
                <p id="fileName" style="color: #111; font-size: 0.85rem; font-weight: 600; margin: 0.5rem 0 0; display: none;"></p>
            </div>
            <input type="file" id="proofFile" name="payment_proof" accept="image/*" style="display: none;"
                   onchange="showFileName(this)">
            {{-- Aperçu --}}
            <div id="imgPreview" style="margin-top: 0.75rem; display: none;">
                <img id="previewImg" src="" style="max-width: 100%; border-radius: 8px; border: 1px solid #e5e7eb;">
                <button type="button" onclick="clearFile()" style="display: block; margin-top: 0.5rem; background: none; border: none; color: #dc2626; font-size: 0.82rem; cursor: pointer;">Choisir une autre image</button>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" id="submitBtn"
                style="display: block; width: 100%; text-align: center; background: #111; color: #fff; font-weight: 800; font-size: 1rem; padding: 1rem; border-radius: 10px; border: none; cursor: pointer; letter-spacing: 0.02em; transition: background 0.2s;"
                onmouseover="this.style.background='#000'" onmouseout="this.style.background='#111'"
                onclick="this.disabled=true; this.textContent='Envoi en cours…'; this.style.opacity='0.7'; this.closest('form').submit();">
            Envoyer la preuve
        </button>
    </form>
</div>

<script>
// Sélection mode paiement
document.querySelectorAll('.method-radio').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.method-card').forEach(card => {
            card.style.borderColor = '#e5e7eb';
            card.style.background = '#fff';
        });
        if (radio.checked) {
            const card = radio.nextElementSibling;
            card.style.borderColor = '#111';
            card.style.background = '#f9fafb';
        }
    });
    // Restore on load if old value
    if (radio.checked) {
        radio.nextElementSibling.style.borderColor = '#111';
        radio.nextElementSibling.style.background = '#f9fafb';
    }
});

function showFileName(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileName').style.display = 'block';
        document.getElementById('dropzoneText').style.display = 'none';

        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function clearFile() {
    document.getElementById('proofFile').value = '';
    document.getElementById('imgPreview').style.display = 'none';
    document.getElementById('fileName').style.display = 'none';
    document.getElementById('dropzoneText').style.display = 'block';
}
</script>
@endsection
