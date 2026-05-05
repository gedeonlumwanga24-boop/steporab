@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="auth-logo">STEPORA</a>
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
