<x-mail::message>
# Bonjour {{ $nom }},

Vous avez reçu une nouvelle réponse de l'équipe **{{ config('app.name') }}** concernant votre message.

---

**Votre message :**
> {{ $messageOriginal }}

---

**Notre réponse :**

{{ $reponse }}

---

Pour consulter la conversation complète et nous répondre, cliquez sur le bouton ci-dessous :

<x-mail::button :url="$lienMessagerie" color="primary">
Voir ma messagerie
</x-mail::button>

Cordialement,
L'équipe **{{ config('app.name') }}**

<small style="color:#9ca3af;">Vous recevez cet email car vous avez contacté notre support. Si vous pensez que c'est une erreur, ignorez ce message.</small>
</x-mail::message>
