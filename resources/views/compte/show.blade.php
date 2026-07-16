@extends('layouts.app')

@section('title', 'Mon Compte — Stepora')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .acct-wrap * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
    .acct-wrap { max-width: 1150px; margin: 2rem auto; padding: 0 1.5rem; }

    /* ---- Header ---- */
    .acct-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; }
    .acct-title-group { display: flex; flex-direction: column; gap: 0.25rem; }
    .acct-title { font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.02em; }
    .acct-subtitle { font-size: 1rem; color: #64748b; margin: 0; }
    .btn-logout { background: #fff; color: #ef4444; border: 1.5px solid #fecaca; border-radius: 8px; padding: 0.6rem 1rem; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 0.5rem; }
    .btn-logout:hover { background: #fef2f2; border-color: #ef4444; }

    /* ---- Layout ---- */
    .acct-layout { display: grid; grid-template-columns: 320px 1fr; gap: 2rem; align-items: start; }
    @media(max-width: 860px) { .acct-layout { grid-template-columns: 1fr; } }

    /* ---- Sidebar Profil ---- */
    .acct-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
    .acct-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
    .acct-card-title { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin: 0; }
    .btn-edit { color: #1d4ed8; font-size: 0.8rem; font-weight: 600; text-decoration: none; padding: 0.3rem 0.6rem; background: #eff6ff; border-radius: 6px; transition: background 0.15s; }
    .btn-edit:hover { background: #dbeafe; }
    .acct-card-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.2rem; }
    
    .info-row { display: flex; flex-direction: column; gap: 0.2rem; }
    .info-label { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
    .info-val { font-size: 0.95rem; font-weight: 500; color: #1e293b; margin: 0; word-break: break-word; }

    /* ---- Liste Commandes ---- */
    .orders-container { display: flex; flex-direction: column; gap: 1.5rem; }
    
    .order-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: box-shadow 0.2s, border-color 0.2s; display: flex; flex-direction: column; }
    .order-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-color: #cbd5e1; }
    
    /* Header commande */
    .order-head { padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .order-id-group { display: flex; flex-direction: column; gap: 0.2rem; }
    .order-id { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0; }
    .order-date { font-size: 0.85rem; color: #64748b; margin: 0; }
    .order-total-block { text-align: right; }
    .order-total { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin: 0; }

    /* Barre de Statuts & Infos (Nouveau design intuitif) */
    .order-status-bar { display: grid; grid-template-columns: 1fr 1fr; background: #fff; border-bottom: 1px solid #f1f5f9; }
    @media(max-width: 600px) { .order-status-bar { grid-template-columns: 1fr; } }
    
    .status-cell { padding: 1rem 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem; border-right: 1px solid #f1f5f9; }
    .status-cell:last-child { border-right: none; }
    @media(max-width: 600px) { .status-cell { border-right: none; border-bottom: 1px solid #f1f5f9; } .status-cell:last-child { border-bottom: none; } }
    
    .status-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .status-content { display: flex; flex-direction: column; gap: 0.15rem; }
    .status-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin: 0; }
    .status-value { font-size: 0.9rem; font-weight: 700; color: #0f172a; margin: 0; }
    .status-desc { font-size: 0.8rem; color: #64748b; margin: 0; margin-top: 0.2rem; }

    /* Couleurs des icônes de statut */
    .icon-success { background: #dcfce7; color: #16a34a; }
    .icon-warning { background: #fef9c3; color: #ca8a04; }
    .icon-error { background: #fee2e2; color: #ef4444; }
    .icon-info { background: #e0e7ff; color: #4f46e5; }
    .icon-neutral { background: #f1f5f9; color: #64748b; }

    /* Liste des produits */
    .order-body { padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; background: #fafafa; }
    .order-item { display: flex; align-items: center; gap: 1rem; background: #fff; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; }
    .order-item-img { width: 52px; height: 52px; object-fit: cover; border-radius: 6px; border: 1px solid #f1f5f9; }
    .order-item-details { flex: 1; min-width: 0; }
    .order-item-name { font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0 0 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .order-item-meta { font-size: 0.85rem; color: #64748b; margin: 0; }
    .order-item-qty { font-size: 1rem; font-weight: 800; color: #0f172a; background: #f1f5f9; padding: 0.2rem 0.6rem; border-radius: 6px; }
    .order-more { font-size: 0.85rem; font-weight: 600; color: #64748b; margin: 0; padding-top: 0.5rem; text-align: center; }

    /* Actions contextuelles */
    .order-action { padding: 1.25rem 1.5rem; background: #fffbeb; border-top: 1px solid #fef3c7; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
    .order-action.danger { background: #fef2f2; border-color: #fee2e2; }
    .action-msg { font-size: 0.9rem; color: #92400e; margin: 0; font-weight: 600; }
    .order-action.danger .action-msg { color: #991b1b; }
    .btn-action { background: #1e293b; color: #fff; padding: 0.75rem 1.25rem; border-radius: 8px; font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: background 0.15s; white-space: nowrap; }
    .btn-action:hover { background: #0f172a; }
    .btn-action.danger { background: #dc2626; }
    .btn-action.danger:hover { background: #b91c1c; }

    .empty-state { text-align: center; padding: 4rem 2rem; background: #fff; border: 1px dashed #cbd5e1; border-radius: 16px; }
    .empty-icon { font-size: 2.5rem; color: #cbd5e1; margin-bottom: 1rem; }
    .empty-msg { font-size: 1rem; color: #64748b; margin: 0 0 1.5rem; font-weight: 500; }
    .btn-shop { background: #1d4ed8; color: #fff; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 700; text-decoration: none; display: inline-block; transition: background 0.15s; }
    .btn-shop:hover { background: #1e40af; }
    
    /* ---- Alerts ---- */
    .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 600; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; }
</style>

<div class="acct-wrap">
    
    <div class="acct-header">
        <div class="acct-title-group">
            <h1 class="acct-title">Mon Compte</h1>
            <p class="acct-subtitle">Bienvenue, {{ $user->name }}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Déconnexion
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="acct-layout">
        
        {{-- COLONNE GAUCHE: PROFIL & SUPPORT --}}
        <aside style="display: flex; flex-direction: column; gap: 1.5rem; position: sticky; top: 2rem;">
            
            <div class="acct-card">
                <div class="acct-card-header">
                    <h3 class="acct-card-title">Mes Informations</h3>
                    <a href="{{ route('compte.edit') }}" class="btn-edit">Modifier</a>
                </div>
                <div class="acct-card-body">
                    <div class="info-row">
                        <span class="info-label">Nom complet</span>
                        <p class="info-val">{{ $user->name }}</p>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <p class="info-val">{{ $user->email }}</p>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Téléphone</span>
                        <p class="info-val">{{ $client->telephone ?? '—' }}</p>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Adresse de livraison</span>
                        <p class="info-val">{{ $client->adresse ?? '—' }}</p>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ville</span>
                        <p class="info-val">{{ $client->ville ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Bloc Support / Messagerie --}}
            <div class="acct-card">
                <div class="acct-card-body" style="background: #f8fafc; text-align: center; padding: 2rem 1.5rem;">
                    <svg width="36" height="36" fill="none" stroke="#64748b" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 0.75rem; display: block;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <p style="font-size: 0.95rem; color: #475569; margin: 0 0 1.25rem; font-weight: 600;">Une question sur votre commande ?</p>
                    <a href="{{ route('messagerie.index') }}" style="display: inline-block; background: #fff; border: 1px solid #cbd5e1; color: #0f172a; font-weight: 700; font-size: 0.9rem; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; transition: all 0.15s; width: 100%;">
                        Contacter le support
                    </a>
                </div>
            </div>

        </aside>

        {{-- COLONNE DROITE: COMMANDES --}}
        <main>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 1.5rem;">Historique des commandes</h2>

            @if($commandes->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="empty-msg">Vous n'avez passé aucune commande pour le moment.</p>
                    <a href="{{ route('produits.index') }}" class="btn-shop">Découvrir la boutique</a>
                </div>
            @else
                <div class="orders-container">
                    @foreach($commandes as $commande)
                        <div class="order-card">
                            
                            {{-- Header de la commande --}}
                            <div class="order-head">
                                <div class="order-id-group">
                                    <p class="order-id">Commande #{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    <p class="order-date">{{ $commande->created_at->translatedFormat('d M Y à H:i') }}</p>
                                </div>
                                <div class="order-total-block">
                                    <p class="order-total">{{ number_format($commande->total, 0, ',', ' ') }} CDF</p>
                                </div>
                            </div>

                            {{-- BARRE DE STATUT & INFOS (Design clair type Amazon/Fintech) --}}
                            <div class="order-status-bar">
                                
                                {{-- 1. Info Paiement --}}
                                @php
                                    $pIconCls = 'icon-neutral';
                                    $pIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'; // Clock
                                    $pText = 'En attente de paiement';
                                    $pDesc = 'Veuillez finaliser votre paiement.';
                                    
                                    if ($commande->payment_status === 'payee') {
                                        $pIconCls = 'icon-success';
                                        $pIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'; // Check
                                        $pText = 'Paiement validé';
                                        $pDesc = $commande->payment_method_label ? 'Payé via ' . $commande->payment_method_label : 'Transaction confirmée';
                                    } elseif ($commande->payment_status === 'en_verification') {
                                        $pIconCls = 'icon-warning';
                                        $pText = 'Paiement en vérification';
                                        $pDesc = 'Validation de la transaction en cours...';
                                    } elseif ($commande->payment_status === 'refusee') {
                                        $pIconCls = 'icon-error';
                                        $pIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>'; // Cross
                                        $pText = 'Paiement échoué';
                                        $pDesc = 'Le paiement a été refusé ou annulé.';
                                    }
                                @endphp
                                <div class="status-cell">
                                    <div class="status-icon {{ $pIconCls }}">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">{!! $pIconSvg !!}</svg>
                                    </div>
                                    <div class="status-content">
                                        <p class="status-label">Paiement</p>
                                        <p class="status-value">{{ $pText }}</p>
                                        <p class="status-desc">{{ $pDesc }}</p>
                                    </div>
                                </div>

                                {{-- 2. Info Livraison --}}
                                @php
                                    $lIconCls = 'icon-neutral';
                                    $lIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>'; // Box
                                    $lText = 'Livraison en attente';
                                    $lDesc = 'En attente de paiement';
                                    
                                    if ($commande->statut === 'payee') {
                                        $lIconCls = 'icon-info';
                                        $lIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'; // Preparation
                                        $lText = 'En préparation';
                                        $lDesc = 'Vos articles sont en cours d\'emballage.';
                                    } elseif ($commande->statut === 'expediee') {
                                        $lIconCls = 'icon-info';
                                        $lIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>'; // Lightning / Fast
                                        $lText = 'Colis expédié';
                                        $lDesc = 'Votre commande est en route.';
                                    } elseif ($commande->statut === 'terminee' || $commande->statut === 'livree') {
                                        $lIconCls = 'icon-success';
                                        $lIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>'; // Check
                                        $lText = 'Colis livré';
                                        $lDesc = 'La commande a été remise avec succès.';
                                    } elseif ($commande->statut === 'annulee') {
                                        $lIconCls = 'icon-error';
                                        $lIconSvg = '<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>'; // Cancelled
                                        $lText = 'Annulée';
                                        $lDesc = 'Cette commande a été annulée.';
                                    }
                                @endphp
                                <div class="status-cell">
                                    <div class="status-icon {{ $lIconCls }}">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">{!! $lIconSvg !!}</svg>
                                    </div>
                                    <div class="status-content">
                                        <p class="status-label">Livraison</p>
                                        <p class="status-value">{{ $lText }}</p>
                                        <p class="status-desc">{{ $lDesc }}</p>
                                    </div>
                                </div>

                            </div>

                            {{-- Liste des produits (Sous forme de boîtes élégantes) --}}
                            <div class="order-body">
                                @foreach($commande->produits as $produit)
                                    <div class="order-item">
                                        @php $imgUrl = $produit->image_url ?? asset('images/default.jpg'); @endphp
                                        <img src="{{ $imgUrl }}" alt="{{ $produit->nom }}" class="order-item-img">
                                        <div class="order-item-details">
                                            <p class="order-item-name">{{ $produit->nom }}</p>
                                            @if(!empty($produit->pivot->taille))
                                                <p class="order-item-meta">Taille : {{ $produit->pivot->taille }}</p>
                                            @endif
                                        </div>
                                        <div class="order-item-qty">&times; {{ $produit->pivot->quantite }}</div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Actions contextuelles (Payer / Réessayer) --}}
                            @if($commande->payment_status === 'non_paye')
                                <div class="order-action">
                                    <p class="action-msg">Finalisez votre commande pour lancer la préparation.</p>
                                    <a href="{{ route('commande.paiement', $commande->id) }}" class="btn-action">Payer maintenant</a>
                                </div>
                            @elseif($commande->payment_status === 'refusee')
                                <div class="order-action danger">
                                    <p class="action-msg">Le paiement a été refusé ou a expiré.</p>
                                    <a href="{{ route('commande.paiement', $commande->id) }}" class="btn-action danger">Réessayer le paiement</a>
                                </div>
                            @elseif($commande->payment_status === 'en_verification' && $commande->pawapay_deposit_id)
                                <div class="order-action" style="background: #f8fafc; border-color: #e2e8f0;">
                                    <p class="action-msg" style="color: #475569;">Le paiement est en cours de traitement par l'opérateur.</p>
                                    <a href="{{ route('commande.pawapay.waiting', $commande->id) }}" class="btn-action" style="background: #e2e8f0; color: #475569;">Suivre l'état</a>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>
</div>
@endsection
