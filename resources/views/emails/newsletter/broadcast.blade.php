<x-mail::message>
@if($type === 'solde')
# 🏷️ Soldes & Promotions — {{ $sujet }}
@elseif($type === 'nouveaute')
# 🆕 Nouveauté — {{ $sujet }}
@else
# 📢 {{ $sujet }}
@endif

Bonjour **{{ $clientNom }}**,

@if($type === 'solde')
Des offres exceptionnelles vous attendent ! Ne manquez pas nos promotions en cours, disponibles pour une durée limitée.
@elseif($type === 'nouveaute')
De nouveaux articles viennent d'arriver dans notre boutique ! Découvrez les dernières nouveautés de la collection **{{ config('app.name') }}**.
@else
Nous avons une annonce importante à vous partager.
@endif

---

{{ $corps }}

---

@if($type === 'solde')
<x-mail::button :url="$lienBoutique" color="error">
🛍️ Profiter des soldes maintenant
</x-mail::button>
@elseif($type === 'nouveaute')
<x-mail::button :url="$lienBoutique" color="primary">
✨ Découvrir les nouveautés
</x-mail::button>
@else
<x-mail::button :url="$lienBoutique" color="primary">
Visiter la boutique
</x-mail::button>
@endif

À très bientôt,<br>
L'équipe **{{ config('app.name') }}**

---

<small style="color:#9ca3af;">Vous recevez cet email car vous êtes inscrit(e) sur {{ config('app.name') }}. Pour ne plus recevoir ces emails, contactez notre support.</small>
</x-mail::message>
