@extends('layouts.admin')

@section('title', 'Nouvel administrateur')

@section('content')

<div style="max-width: 600px; margin: 0 auto;">

    <a href="{{ route('admin.admins.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; font-size: 0.9rem; text-decoration: none; margin-bottom: 2rem;">
        <i class="fa-solid fa-arrow-left"></i> Retour à la liste
    </a>

    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="fa-solid fa-user-shield" style="margin-right: 8px; color: #6366f1;"></i>
                Créer un compte administrateur
            </h3>
        </div>

        <div style="padding: 2rem;">
            <div style="background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 0.75rem;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #d97706; margin-top: 2px; flex-shrink: 0;"></i>
                <div>
                    <strong style="color: #92400e; font-size: 0.9rem;">Action sensible</strong>
                    <p style="color: #78350f; font-size: 0.85rem; margin: 0.25rem 0 0;">Ce compte aura un accès complet au panel d'administration. N'accordez ces droits qu'à des personnes de confiance.</p>
                </div>
            </div>

            @if($errors->any())
                <div class="admin-alert admin-alert-error" style="margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-circle-exclamation" style="margin-right: 5px;"></i>
                    <ul style="margin: 0.5rem 0 0 1rem; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf

                <div class="admin-form-group" style="margin-bottom: 1.5rem;">
                    <label class="admin-label">Nom complet <span style="color: #ef4444;">*</span></label>
                    <input type="text"
                           name="name"
                           class="admin-input"
                           value="{{ old('name') }}"
                           placeholder="Jean Dupont"
                           required>
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.5rem;">
                    <label class="admin-label">Adresse email <span style="color: #ef4444;">*</span></label>
                    <input type="email"
                           name="email"
                           class="admin-input"
                           value="{{ old('email') }}"
                           placeholder="admin@example.com"
                           required>
                </div>

                <div class="admin-form-group" style="margin-bottom: 1.5rem;">
                    <label class="admin-label">Mot de passe <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="password"
                               name="password"
                               id="password"
                               class="admin-input"
                               placeholder="Minimum 8 caractères"
                               required
                               style="padding-right: 3rem;">
                        <button type="button"
                                onclick="togglePwd('password', this)"
                                style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="admin-form-group" style="margin-bottom: 2rem;">
                    <label class="admin-label">Confirmer le mot de passe <span style="color: #ef4444;">*</span></label>
                    <div style="position: relative;">
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="admin-input"
                               placeholder="Répétez le mot de passe"
                               required
                               style="padding-right: 3rem;">
                        <button type="button"
                                onclick="togglePwd('password_confirmation', this)"
                                style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--admin-border);">
                    <a href="{{ route('admin.admins.index') }}" class="admin-input" style="text-align: center; text-decoration: none; padding: 0.65rem 1.5rem; background: transparent; color: #6b7280; cursor: pointer; font-size: 0.9rem;">
                        Annuler
                    </a>
                    <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-user-shield"></i> Créer l'administrateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}
</script>

@endsection
