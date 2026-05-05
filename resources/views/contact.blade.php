@extends('layouts.app')

@section('content')

<div class="contact-page">
    <div class="contact-container">

        {{-- HEADER --}}
        <div class="contact-header">
            <p class="contact-eyebrow">Assistance & Support</p>
            <h1 class="contact-title">Contactez-nous</h1>
            <p class="contact-desc">
                Notre équipe est à votre disposition pour répondre à toutes vos questions.
                Envoyez-nous un message et nous vous répondrons dans les plus brefs délais.
            </p>
        </div>

        {{-- GRID : Infos + Formulaire --}}
        <div class="contact-grid">

            {{-- INFOS DE CONTACT --}}
            <div class="contact-info-list">

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Notre Boutique</h3>
                        <p>123 Avenue de la Mode<br>Kinshasa, RDC</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Téléphone</h3>
                        <p>+243 81 000 0000</p>
                        <p>Lun – Sam, 8h00 – 18h00</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>Email</h3>
                        <p>support@stepora.cd</p>
                        <p>contact@stepora.cd</p>
                    </div>
                </div>

                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="contact-info-content">
                        <h3>WhatsApp</h3>
                        <p><a href="https://wa.me/243970297987" style="color:inherit">+243 97 029 7987</a></p>
                    </div>
                </div>

            </div>

            {{-- FORMULAIRE --}}
            <div class="contact-form-card">

                @if(session('success'))
                    <div class="contact-alert contact-alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="contact-form-group">
                        <label for="nom" class="contact-label">Nom complet</label>
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            class="contact-input"
                            value="{{ old('nom') }}"
                            placeholder="Ex : Jean Dupont"
                            required
                        >
                        @error('nom')
                            <div class="contact-alert contact-alert-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="contact-form-group">
                        <label for="email" class="contact-label">Adresse email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="contact-input"
                            value="{{ old('email') }}"
                            placeholder="Ex : jean.dupont@email.com"
                            required
                        >
                        @error('email')
                            <div class="contact-alert contact-alert-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="contact-form-group">
                        <label for="message" class="contact-label">Votre message</label>
                        <textarea
                            id="message"
                            name="message"
                            class="contact-input contact-textarea"
                            placeholder="Comment pouvons-nous vous aider ?"
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <div class="contact-alert contact-alert-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="contact-submit">
                        Envoyer le message
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection
