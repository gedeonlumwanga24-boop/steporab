<x-mail::message>
# ✅ Votre paiement a été confirmé !

Bonjour **{{ $client->nom ?? $client->name }}**,

Bonne nouvelle ! Votre paiement pour la commande **#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}** a été validé avec succès par notre équipe.

---

## Récapitulatif de votre commande

<x-mail::table>
| Produit | Qté | Prix unitaire | Sous-total |
|:--------|:---:|:-------------:|:----------:|
@foreach($produits as $produit)
| {{ $produit->nom }} | {{ $produit->pivot->quantite }} | {{ number_format($produit->pivot->prix_unitaire, 0, ',', ' ') }} CDF | {{ number_format($produit->pivot->quantite * $produit->pivot->prix_unitaire, 0, ',', ' ') }} CDF |
@endforeach
</x-mail::table>

**Total payé : {{ number_format($commande->total, 0, ',', ' ') }} CDF**

---

**Détails de la commande :**
- 📦 Numéro de commande : **#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}**
- 📅 Date : **{{ $commande->created_at->format('d/m/Y à H:i') }}**
- 💳 Mode de paiement : **{{ $commande->payment_method_label }}**
- 📍 Adresse de livraison : **{{ $commande->adresse }}**

---

Votre commande est maintenant en cours de traitement. Vous pouvez suivre son statut depuis votre espace client.

<x-mail::button :url="$lienCompte" color="success">
Voir mon espace client
</x-mail::button>

Merci pour votre confiance,<br>
L'équipe **{{ config('app.name') }}**

<small style="color:#9ca3af;">Si vous n'êtes pas à l'origine de cette commande, contactez-nous immédiatement.</small>
</x-mail::message>
