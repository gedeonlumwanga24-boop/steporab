@extends('layouts.app')

@section('content')
<div class="success-page">
    <div class="success-container">
        <!-- Animation du checkmark -->
        <div class="success-animation">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>

        <h1 class="success-title">Commande validée !</h1>
        <p class="success-message">
            Merci pour votre commande. Elle a bien été prise en compte et est actuellement en cours de traitement.<br>
            Vous pouvez suivre l'état de votre commande depuis votre espace client.
        </p>

        <div class="success-actions">
            <a href="{{ route('compte.show') }}" class="btn-primary">Voir mes commandes</a>
            <a href="{{ route('produits.index') }}" class="btn-secondary">Continuer mes achats</a>
        </div>
    </div>
</div>

<style>
/* Styles de la page de succès */
.success-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    background-color: #f8fafc;
}

.success-container {
    background: #ffffff;
    padding: 3rem;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.success-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 1rem;
    margin-top: 1.5rem;
}

.success-message {
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 2.5rem;
}

.success-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-primary {
    display: block;
    background: #000;
    color: #fff;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-primary:hover {
    background: #1f2937;
    transform: translateY(-2px);
}

.btn-secondary {
    display: block;
    background: #f3f4f6;
    color: #111827;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

/* Animation CSS du checkmark */
.success-animation {
    display: flex;
    justify-content: center;
}

.checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #10b981; /* Vert de succès */
    stroke-miterlimit: 10;
    box-shadow: inset 0px 0px 0px #10b981;
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}

.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: #10b981;
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}

@keyframes scale {
    0%, 100% { transform: none; }
    50% { transform: scale3d(1.1, 1.1, 1); }
}

@keyframes fill {
    100% { box-shadow: inset 0px 0px 0px 30px rgba(16, 185, 129, 0.1); }
}
</style>
@endsection
