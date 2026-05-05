@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="auth-logo">STEPORA</a>
            <h1 class="auth-title">Connexion</h1>
            <p class="auth-desc">Connectez-vous pour accéder à votre compte</p>
        </div>

        @if(session('success'))
            <div class="auth-alert" style="background:#ecfdf5; color:#065f46; border:1px solid #d1fae5;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="auth-alert auth-alert-error">
                <ul style="margin:0; padding-left:1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="auth-form-group">
                <label for="email" class="auth-label">Email</label>
                <input type="email" id="email" name="email" class="auth-input" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="auth-form-group">
                <label for="password" class="auth-label">Mot de passe</label>
                <input type="password" id="password" name="password" class="auth-input" required>
            </div>

            <div class="auth-options">
                <label class="auth-checkbox">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Se souvenir de moi
                </label>
            </div>

            <button type="submit" class="auth-submit">Se connecter</button>

            <div class="auth-footer">
                Vous n'avez pas de compte ? <a href="{{ route('register') }}" class="auth-link">S'inscrire</a>
            </div>
        </form>
    </div>
</div>
@endsection
