@extends('layouts.app')

@section('content')
<div class="compte-page">
    <div class="compte-header">
        <div>
            <h1 class="compte-title">Modifier mon profil</h1>
            <p class="compte-subtitle"><a href="{{ route('compte.show') }}" style="color:inherit;text-decoration:underline;">← Retour à mon compte</a></p>
        </div>
    </div>

    <div class="compte-layout" style="display:block; max-width: 600px; margin: 0 auto;">
        <div class="compte-card">
            
            @if($errors->any())
                <div class="auth-alert auth-alert-error">
                    <ul style="margin:0; padding-left:1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('compte.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="auth-form-group">
                    <label for="name" class="auth-label">Nom complet</label>
                    <input type="text" id="name" name="name" class="auth-input" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="auth-form-group">
                    <label class="auth-label">Email (non modifiable ici)</label>
                    <input type="email" class="auth-input" value="{{ $user->email }}" disabled style="background:#e5e7eb; cursor:not-allowed;">
                </div>

                <div class="auth-form-group">
                    <label for="telephone" class="auth-label">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" class="auth-input" value="{{ old('telephone', $client->telephone ?? '') }}" placeholder="+243 ...">
                </div>

                <div class="auth-form-group">
                    <label for="adresse" class="auth-label">Adresse de livraison</label>
                    <input type="text" id="adresse" name="adresse" class="auth-input" value="{{ old('adresse', $client->adresse ?? '') }}" placeholder="Avenue, Quartier, Commune...">
                </div>

                <div class="auth-form-group">
                    <label for="ville" class="auth-label">Ville</label>
                    <input type="text" id="ville" name="ville" class="auth-input" value="{{ old('ville', $client->ville ?? '') }}" placeholder="Kinshasa, Lubumbashi...">
                </div>

                <button type="submit" class="auth-submit" style="margin-top: 1.5rem;">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>
@endsection
