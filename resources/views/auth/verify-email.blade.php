@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="auth-logo">STEPORA</a>
            <h1 class="auth-title">Vérifiez votre email</h1>
            <p class="auth-desc">Un lien de vérification a été envoyé à votre adresse email.</p>
        </div>

        @if(session('success'))
            <div class="auth-alert" style="background:#ecfdf5; color:#065f46; border:1px solid #d1fae5;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="auth-alert auth-alert-error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Icône email -->
        <div style="text-align:center; margin: 1.5rem 0;">
            <div style="display:inline-flex; align-items:center; justify-content:center;
                        width:72px; height:72px; border-radius:50%;
                        background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>
        </div>

        <p style="text-align:center; color:#4b5563; font-size:0.9rem; margin-bottom:1.5rem; line-height:1.6;">
            Avant de continuer, veuillez vérifier votre email en cliquant sur le lien
            que nous venons de vous envoyer. Si vous n'avez pas reçu l'email, vérifiez
            votre dossier spam.
        </p>

        <!-- Renvoyer l'email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-submit">
                Renvoyer l'email de vérification
            </button>
        </form>

        <div class="auth-footer" style="margin-top:1rem;">
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:none; border:none; color:#6b7280;
                                             font-size:0.875rem; cursor:pointer; text-decoration:underline;">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
