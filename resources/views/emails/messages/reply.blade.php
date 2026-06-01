<x-mail::message>
# Bonjour {{ $nom }},

Vous avez reçu une réponse de l'équipe **Stepora** concernant votre message.

**Votre message :**
> {{ $messageOriginal }}

**Notre réponse :**
{{ $reponse }}

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
