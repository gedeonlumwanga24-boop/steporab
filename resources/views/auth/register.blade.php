@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="auth-logo" style="display: inline-flex; align-items: center; justify-content: center; width: 100%;">
                <img src="{{ asset('logo.jpg') }}" alt="The Box" style="height: 80px; width: auto; object-fit: contain;">
            </a>
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-desc">Rejoignez-nous pour gérer vos commandes</p>
        </div>

        @if($errors->any())
            <div class="auth-alert auth-alert-error">
                <ul style="margin:0; padding-left:1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- GOOGLE EN PREMIER --}}
        <a href="{{ route('google.redirect') }}" class="auth-google-btn" style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; background: #fff; border: 2px solid #e5e7eb; border-radius: 12px; padding: 0.9rem 1.25rem; font-size: 1rem; font-weight: 700; color: #111; text-decoration: none; transition: all 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 1.5rem;"
           onmouseover="this.style.borderColor='#4285F4'; this.style.boxShadow='0 4px 14px rgba(66,133,244,0.15)'; this.style.transform='translateY(-1px)';"
           onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.06)'; this.style.transform='translateY(0)';">
            <svg width="22" height="22" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            </svg>
            S'inscrire avec Google
        </a>

        {{-- Séparateur --}}
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="flex: 1; height: 1px; background: #e5e7eb;"></div>
            <span style="color: #9ca3af; font-size: 0.82rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; white-space: nowrap;">ou avec un email</span>
            <div style="flex: 1; height: 1px; background: #e5e7eb;"></div>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="auth-form-group">
                <label for="name" class="auth-label">Nom complet</label>
                <input type="text" id="name" name="name" class="auth-input" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="auth-form-group">
                <label for="email" class="auth-label">Email</label>
                <input type="email" id="email" name="email" class="auth-input" value="{{ old('email') }}" required>
            </div>

            <div class="auth-form-group">
                <label for="password" class="auth-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="auth-input" required>
            </div>

            <div class="auth-form-group">
                <label for="password_confirmation" class="auth-label">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="auth-input" required>
            </div>

            <button type="submit" class="auth-submit" style="margin-top: 1rem;">S'inscrire</button>

            <div class="auth-footer">
                Déjà un compte ? <a href="{{ route('login') }}" class="auth-link">Se connecter</a>
            </div>
        </form>
    </div>
</div>
@endsection
