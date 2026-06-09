@extends('layouts.app')

@section('title', 'Preuve de paiement — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')
<div style="max-width: 580px; margin: 3rem auto; padding: 0 1rem;">

    {{-- Header --}}
    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
        <a href="{{ route('commande.paiement', $commande->id) }}" style="color: #6b7280; text-decoration: none; display: flex; align-items: center; gap: 0.4rem; font-size: 0.95rem; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#111'" onmouseout="this.style.color='#6b7280'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Retour au paiement
        </a>
    </div>

    <h1 style="font-size: 2rem; font-weight: 900; color: #111; margin: 0 0 0.5rem; letter-spacing: -0.02em;">Confirmation</h1>
    <p style="color: #6b7280; margin: 0 0 2rem; font-size: 1.05rem;">Veuillez fournir les détails de votre transaction pour la commande <strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></p>

    {{-- Flash messages --}}
    @if(session('error'))
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 1.25rem; margin-bottom: 1.5rem; color: #991b1b; font-size: 0.95rem; font-weight: 500; border-radius: 0 8px 8px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 1.25rem; margin-bottom: 1.5rem; color: #991b1b; font-size: 0.95rem; font-weight: 500; border-radius: 0 8px 8px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('commande.submitProof', $commande->id) }}" method="POST" enctype="multipart/form-data" style="background: #fff; padding: 2.5rem; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        @csrf

        {{-- Mode de paiement --}}
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 800; color: #111; font-size: 1rem; margin-bottom: 1rem;">Quel réseau avez-vous utilisé ? <span style="color: #ef4444;">*</span></label>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem;">
                
                <label style="cursor: pointer; display: block;">
                    <input type="radio" name="payment_method" value="mpesa" {{ old('payment_method') == 'mpesa' ? 'checked' : '' }} style="display:none;" class="method-radio">
                    <div class="method-card" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.25rem 1rem; text-align: center; transition: all 0.2s; background: #fff;">
                        <div style="width: 56px; height: 56px; border-radius: 10px; overflow: hidden; background: #fff; border: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                            <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <span style="font-weight: 700; font-size: 0.95rem; color: #111;">M-Pesa</span>
                    </div>
                </label>
                
                <label style="cursor: pointer; display: block;">
                    <input type="radio" name="payment_method" value="orange_money" {{ old('payment_method') == 'orange_money' ? 'checked' : '' }} style="display:none;" class="method-radio">
                    <div class="method-card" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.25rem 1rem; text-align: center; transition: all 0.2s; background: #fff;">
                        <div style="width: 56px; height: 56px; border-radius: 10px; overflow: hidden; background: #111; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                            <img src="{{ asset('images/orange_money.png') }}" alt="Orange Money" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <span style="font-weight: 700; font-size: 0.95rem; color: #111;">Orange</span>
                    </div>
                </label>

                <label style="cursor: pointer; display: block;">
                    <input type="radio" name="payment_method" value="airtel_money" {{ old('payment_method') == 'airtel_money' ? 'checked' : '' }} style="display:none;" class="method-radio">
                    <div class="method-card" style="border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.25rem 1rem; text-align: center; transition: all 0.2s; background: #fff;">
                        <div style="width: 56px; height: 56px; border-radius: 10px; overflow: hidden; background: #fff; border: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem;">
                            <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel Money" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <span style="font-weight: 700; font-size: 0.95rem; color: #111;">Airtel</span>
                    </div>
                </label>
            </div>
        </div>

        {{-- Numéro utilisé --}}
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 800; color: #111; font-size: 1rem; margin-bottom: 0.5rem;">Votre numéro d'envoi <span style="color: #ef4444;">*</span></label>
            <div style="position: relative;">
                <div style="position: absolute; inset-y: 0; left: 0; padding-left: 1rem; display: flex; align-items: center; pointer-events: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                </div>
                <input type="text" name="payment_phone" value="{{ old('payment_phone') }}" placeholder="Ex: 0991 234 567"
                       style="width: 100%; padding: 1rem 1rem 1rem 2.75rem; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1rem; color: #111; box-sizing: border-box; outline: none; transition: border-color 0.2s; font-weight: 500;"
                       onfocus="this.style.borderColor='#111'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>

        {{-- Référence --}}
        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 800; color: #111; font-size: 1rem; margin-bottom: 0.5rem;">N° de référence de la transaction <span style="color: #ef4444;">*</span></label>
            <div style="position: relative;">
                <div style="position: absolute; inset-y: 0; left: 0; padding-left: 1rem; display: flex; align-items: center; pointer-events: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Ex: MP2109X8A..."
                       style="width: 100%; padding: 1rem 1rem 1rem 2.75rem; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 1rem; color: #111; box-sizing: border-box; outline: none; transition: border-color 0.2s; font-weight: 500;"
                       onfocus="this.style.borderColor='#111'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>

        {{-- Capture d'écran --}}
        <div style="margin-bottom: 2.5rem;">
            <label style="display: block; font-weight: 800; color: #111; font-size: 1rem; margin-bottom: 0.5rem;">Capture d'écran SMS <span style="color: #ef4444;">*</span></label>
            <div id="dropzone" style="border: 2px dashed #d1d5db; border-radius: 12px; padding: 2.5rem 1rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #f9fafb;"
                 onclick="document.getElementById('proofFile').click()"
                 onmouseover="this.style.borderColor='#9ca3af'; this.style.background='#f3f4f6';"
                 onmouseout="this.style.borderColor='#d1d5db'; this.style.background='#f9fafb';"
                 ondragover="event.preventDefault(); this.style.borderColor='#111'; this.style.background='#f3f4f6';"
                 ondragleave="this.style.borderColor='#d1d5db'; this.style.background='#f9fafb';">
                
                <div style="width: 56px; height: 56px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <p style="color: #111; font-size: 1rem; font-weight: 700; margin: 0 0 0.25rem;" id="dropzoneTitle">Uploader votre image</p>
                <p style="color: #6b7280; font-size: 0.85rem; margin: 0;" id="dropzoneText">Formats supportés : JPG, PNG (Max 10 Mo)</p>
                <p id="fileName" style="color: #111; font-size: 0.95rem; font-weight: 700; margin: 0.5rem 0 0; display: none; padding: 0.5rem; background: #e5e7eb; border-radius: 6px;"></p>
            </div>
            
            <input type="file" id="proofFile" name="payment_proof" accept="image/*" style="display: none;"
                   onchange="showFileName(this)">
            
            {{-- Aperçu --}}
            <div id="imgPreview" style="margin-top: 1rem; display: none; text-align: center;">
                <div style="position: relative; display: inline-block;">
                    <img id="previewImg" src="" style="max-width: 100%; max-height: 250px; border-radius: 12px; border: 2px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <button type="button" onclick="clearFile()" style="position: absolute; top: 0.5rem; right: 0.5rem; width: 32px; height: 32px; background: #fff; border: 1px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #ef4444; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" id="submitBtn"
                style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; text-align: center; background: #111; color: #fff; font-weight: 800; font-size: 1.05rem; padding: 1.25rem; border-radius: 12px; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(0,0,0,0.2)';" 
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                onclick="this.disabled=true; this.innerHTML='<svg class=\'animate-spin\' width=\'20\' height=\'20\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2.5\' stroke-linecap=\'round\' stroke-linejoin=\'round\' style=\'animation: spin 1s linear infinite;\'><path d=\'M21 12a9 9 0 1 1-6.219-8.56\'/></svg> Traitement en cours...'; this.style.opacity='0.9'; this.style.transform='none'; this.style.boxShadow='none'; this.closest('form').submit();">
            Soumettre pour vérification
        </button>
    </form>
</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .method-radio:checked + .method-card {
        border-color: #111 !important;
        background: #fafafa !important;
        box-shadow: 0 0 0 2px #111 !important;
    }
    .method-card:hover {
        border-color: #9ca3af;
    }
</style>

<script>
function showFileName(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileName').style.display = 'block';
        document.getElementById('dropzoneTitle').style.display = 'none';
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
    document.getElementById('dropzoneTitle').style.display = 'block';
    document.getElementById('dropzoneText').style.display = 'block';
}

// Support pour Drag & Drop
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('proofFile');

dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.style.borderColor = '#111';
    dropzone.style.background = '#f3f4f6';
});

dropzone.addEventListener('dragleave', () => {
    dropzone.style.borderColor = '#d1d5db';
    dropzone.style.background = '#f9fafb';
});

dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.style.borderColor = '#d1d5db';
    dropzone.style.background = '#f9fafb';
    
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        showFileName(fileInput);
    }
});
</script>
@endsection
