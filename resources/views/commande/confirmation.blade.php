@extends('layouts.app')

@section('title', 'Paiement envoyé — Commande #' . str_pad($commande->id, 5, '0', STR_PAD_LEFT))

@section('content')

{{-- Fond animé --}}
<div style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; position: relative; overflow: hidden;">

    {{-- Cercles décoratifs en arrière-plan --}}
    <div style="position: fixed; top: -100px; right: -100px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
    <div style="position: fixed; bottom: -80px; left: -80px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(5,150,105,0.06) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

    <div style="max-width: 560px; width: 100%; position: relative; z-index: 1;">

        {{-- Icône succès animée --}}
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #059669, #10b981); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 0 0 0 rgba(16,185,129,0.4); animation: pulse 2.5s ease-in-out infinite, scaleIn 0.6s cubic-bezier(0.34,1.56,0.64,1) both;">
                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: fadeIn 0.4s ease 0.4s both;">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <h1 style="font-size: 2.25rem; font-weight: 900; color: #111; margin: 0 0 0.5rem; letter-spacing: -0.02em; animation: fadeUp 0.5s ease 0.3s both;">Envoyé avec succès !</h1>
            <p style="color: #4b5563; font-size: 1.05rem; margin: 0; line-height: 1.7; animation: fadeUp 0.5s ease 0.45s both;">
                Votre preuve de paiement a bien été reçue.<br>
                Notre équipe la vérifie dans les plus brefs délais.
            </p>
        </div>

        {{-- Reçu --}}
        <div style="background: #fff; border-radius: 20px; border: 1px solid #e5e7eb; box-shadow: 0 8px 30px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 1.5rem; animation: fadeUp 0.5s ease 0.5s both;">

            {{-- Header reçu --}}
            <div style="background: linear-gradient(135deg, #111 0%, #374151 100%); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p style="color: #9ca3af; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 0.3rem; font-weight: 600;">Commande</p>
                    <p style="color: #fff; font-size: 1.6rem; font-weight: 900; margin: 0;">#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div style="text-align: right;">
                    <p style="color: #9ca3af; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 0.3rem; font-weight: 600;">Total payé</p>
                    <p style="color: #fff; font-size: 1.6rem; font-weight: 900; margin: 0;">{{ number_format($commande->total, 0, ' ', ' ') }} <span style="font-size: 0.85rem; color: #6b7280;">CDF</span></p>
                </div>
            </div>

            {{-- Détails --}}
            <div style="padding: 0 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 0.9rem; font-weight: 500;">Date</span>
                    <span style="font-weight: 700; font-size: 0.9rem; color: #111;">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="color: #6b7280; font-size: 0.9rem; font-weight: 500;">Réseau utilisé</span>
                    <span style="font-weight: 700; font-size: 0.9rem; color: #111;">{{ $commande->payment_method_label }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                    <span style="color: #6b7280; font-size: 0.9rem; font-weight: 500;">Statut</span>
                    <span style="background: #fef3c7; color: #92400e; font-size: 0.8rem; font-weight: 800; padding: 0.35rem 0.9rem; border-radius: 999px; display: inline-flex; align-items: center; gap: 0.45rem;">
                        <span style="width: 7px; height: 7px; background: #d97706; border-radius: 50%; display: inline-block; animation: blink 1.5s ease-in-out infinite;"></span>
                        En vérification
                    </span>
                </div>
            </div>
        </div>

        {{-- Étapes de suivi --}}
        <div style="background: #fff; border-radius: 20px; border: 1px solid #e5e7eb; padding: 1.75rem 2rem; margin-bottom: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.04); animation: fadeUp 0.5s ease 0.6s both;">
            <h2 style="font-size: 1rem; font-weight: 800; color: #111; margin: 0 0 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Que se passe-t-il ensuite ?
            </h2>

            {{-- Étape 1 --}}
            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 38px; height: 38px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 0 5px rgba(5,150,105,0.1);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div style="width: 2px; height: 40px; background: linear-gradient(to bottom, #059669 0%, #e5e7eb 100%); margin: 4px 0;"></div>
                </div>
                <div style="padding-top: 0.45rem; padding-bottom: 1.2rem;">
                    <p style="font-weight: 800; color: #059669; margin: 0 0 0.2rem; font-size: 0.95rem;">Preuve reçue ✓</p>
                    <p style="color: #6b7280; font-size: 0.85rem; margin: 0; line-height: 1.5;">Votre capture d'écran a bien été enregistrée dans notre système.</p>
                </div>
            </div>

            {{-- Étape 2 --}}
            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                    <div style="width: 38px; height: 38px; background: #fffbeb; border: 2.5px solid #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <span style="width: 11px; height: 11px; background: #f59e0b; border-radius: 50%; display: block; animation: blink 1.5s ease-in-out infinite;"></span>
                    </div>
                    <div style="width: 2px; height: 40px; background: #e5e7eb; margin: 4px 0;"></div>
                </div>
                <div style="padding-top: 0.45rem; padding-bottom: 1.2rem;">
                    <p style="font-weight: 800; color: #92400e; margin: 0 0 0.2rem; font-size: 0.95rem;">Vérification en cours…</p>
                    <p style="color: #6b7280; font-size: 0.85rem; margin: 0; line-height: 1.5;">Notre équipe confirme votre paiement sous 24h maximum.</p>
                </div>
            </div>

            {{-- Étape 3 --}}
            <div style="display: flex; gap: 1rem; align-items: flex-start;">
                <div style="flex-shrink: 0;">
                    <div style="width: 38px; height: 38px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
                <div style="padding-top: 0.45rem;">
                    <p style="font-weight: 800; color: #9ca3af; margin: 0 0 0.2rem; font-size: 0.95rem;">Commande confirmée & expédiée</p>
                    <p style="color: #9ca3af; font-size: 0.85rem; margin: 0; line-height: 1.5;">Après validation, votre commande sera préparée et expédiée rapidement.</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display: flex; flex-direction: column; gap: 0.75rem; animation: fadeUp 0.5s ease 0.7s both;">
            <a href="{{ route('compte.show') }}"
               style="display: flex; align-items: center; justify-content: center; gap: 0.65rem; background: #111; color: #fff; font-weight: 800; font-size: 1rem; padding: 1.15rem; border-radius: 12px; text-decoration: none; transition: all 0.25s; box-shadow: 0 4px 14px rgba(0,0,0,0.15);"
               onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.22)';"
               onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(0,0,0,0.15)';">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                Suivre mes commandes
            </a>
            <a href="{{ route('produits.index') }}"
               style="display: flex; align-items: center; justify-content: center; gap: 0.65rem; background: #f9fafb; color: #374151; font-weight: 700; font-size: 1rem; padding: 1.15rem; border-radius: 12px; text-decoration: none; border: 1.5px solid #e5e7eb; transition: all 0.2s;"
               onmouseover="this.style.background='#f3f4f6';"
               onmouseout="this.style.background='#f9fafb';">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Continuer mes achats
            </a>
        </div>

    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); }
    50%       { box-shadow: 0 0 0 20px rgba(16,185,129,0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.6); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.25; }
}
</style>

@endsection
