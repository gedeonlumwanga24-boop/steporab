@extends('layouts.admin')

@section('title', 'Envoyer une Notification')

@section('content')

<div style="max-width: 760px;">

    {{-- En-tête --}}
    <div class="admin-card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="width: 52px; height: 52px; background: #3b82f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-paper-plane" style="color: white; font-size: 1.3rem;"></i>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 1.3rem; font-weight: 700;">Notifications Clients</h2>
                <p style="margin: 0.25rem 0 0; color: #6b7280; font-size: 0.9rem;">
                    Envoyez un email groupé à tous vos clients
                    &nbsp;·&nbsp;
                    <strong style="color: #111;">{{ $totalClients }}</strong> client(s) inscrits
                </p>
            </div>
        </div>
    </div>

    {{-- Type cards --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <label class="type-card" for="type_solde" style="cursor: pointer;">
            <input type="radio" id="type_solde" name="type_preview" value="solde" style="display:none;">
            <div style="text-align: center; padding: 1.25rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; transition: all .2s;">
                <i class="fa-solid fa-tag" style="font-size: 1.8rem; color: #f59e0b;"></i>
                <p style="margin: 0.5rem 0 0; font-weight: 700; font-size: 0.9rem;">Soldes</p>
                <p style="margin: 0.2rem 0 0; color: #6b7280; font-size: 0.78rem;">Promotions & offres</p>
            </div>
        </label>
        <label class="type-card" for="type_nouveaute" style="cursor: pointer;">
            <input type="radio" id="type_nouveaute" name="type_preview" value="nouveaute" style="display:none;">
            <div style="text-align: center; padding: 1.25rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; transition: all .2s;">
                <i class="fa-solid fa-star" style="font-size: 1.8rem; color: #10b981;"></i>
                <p style="margin: 0.5rem 0 0; font-weight: 700; font-size: 0.9rem;">Nouveautés</p>
                <p style="margin: 0.2rem 0 0; color: #6b7280; font-size: 0.78rem;">Nouveaux articles</p>
            </div>
        </label>
        <label class="type-card" for="type_general" style="cursor: pointer;">
            <input type="radio" id="type_general" name="type_preview" value="general" style="display:none;" checked>
            <div style="text-align: center; padding: 1.25rem 1rem; border: 2px solid #3b82f6; border-radius: 8px; background: #eff6ff; transition: all .2s;">
                <i class="fa-solid fa-bullhorn" style="font-size: 1.8rem; color: #3b82f6;"></i>
                <p style="margin: 0.5rem 0 0; font-weight: 700; font-size: 0.9rem;">Général</p>
                <p style="margin: 0.2rem 0 0; color: #6b7280; font-size: 0.78rem;">Annonce libre</p>
            </div>
        </label>
    </div>

    {{-- Formulaire --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title"><i class="fa-solid fa-pen-to-square" style="margin-right: 8px; color: #3b82f6;"></i> Composer le message</h3>
        </div>
        <div style="padding: 1.5rem;">
            <form method="POST" action="{{ route('admin.newsletter.send') }}" id="newsletterForm">
                @csrf

                {{-- Type caché --}}
                <input type="hidden" name="type" id="typeHidden" value="general">

                {{-- Sujet --}}
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #374151; margin-bottom: .5rem;">
                        Sujet de l'email <span style="color: #dc2626;">*</span>
                    </label>
                    <input
                        type="text"
                        name="sujet"
                        value="{{ old('sujet') }}"
                        placeholder="Ex: Soldes d'été — jusqu'à -40% !"
                        required
                        style="width: 100%; box-sizing: border-box; padding: .8rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: .95rem; font-family: inherit; transition: border-color .2s;"
                        onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"
                    >
                    @error('sujet') <p style="color: #dc2626; font-size: .8rem; margin-top: .35rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Message --}}
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #374151; margin-bottom: .5rem;">
                        Contenu du message <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea
                        name="message"
                        rows="7"
                        placeholder="Rédigez votre message ici...&#10;Ex: Profitez de nos soldes exceptionnelles sur toute la collection jusqu'au 30 juin. Des réductions allant jusqu'à -40% sur une sélection de produits."
                        required
                        style="width: 100%; box-sizing: border-box; padding: .8rem 1rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: .95rem; font-family: inherit; resize: vertical; transition: border-color .2s;"
                        onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'"
                    >{{ old('message') }}</textarea>
                    @error('message') <p style="color: #dc2626; font-size: .8rem; margin-top: .35rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Avertissement --}}
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #3b82f6; border-radius: 4px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; display: flex; gap: .75rem; align-items: flex-start;">
                    <i class="fa-solid fa-circle-info" style="color: #3b82f6; margin-top: .1rem;"></i>
                    <div>
                        <p style="margin: 0; font-weight: 600; font-size: .875rem; color: #1e293b;">Attention à l'envoi</p>
                        <p style="margin: .25rem 0 0; font-size: .82rem; color: #475569;">
                            Cet email sera envoyé à <strong>{{ $totalClients }} client(s)</strong>. Cette action est irréversible. Vérifiez bien votre message avant d'envoyer.
                        </p>
                    </div>
                </div>

                {{-- Bouton --}}
                <button type="submit" class="btn-primary" id="sendBtn"
                    style="display: inline-flex; align-items: center; gap: .6rem; padding: .85rem 2rem; border: none; font-size: .95rem; font-weight: 600; cursor: pointer;"
                    onclick="this.disabled=true; this.innerHTML='<i class=\'fa-solid fa-spinner fa-spin\'></i> Envoi en cours...'; this.form.submit();"
                >
                    <i class="fa-solid fa-paper-plane"></i>
                    Envoyer à tous les clients ({{ $totalClients }})
                </button>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Gestion des cartes de type
    document.querySelectorAll('.type-card').forEach(label => {
        label.addEventListener('click', function () {
            const val = this.querySelector('input').value;
            document.getElementById('typeHidden').value = val;

            // Reset all cards
            document.querySelectorAll('.type-card > div').forEach(div => {
                div.style.borderColor = '#e5e7eb';
                div.style.background  = 'transparent';
            });

            // Highlight selected
            const selected = this.querySelector('div');
            selected.style.borderColor = '#3b82f6';
            selected.style.background  = '#eff6ff';
        });
    });
</script>
@endpush
