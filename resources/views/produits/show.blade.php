<!DOCTYPE html>
<html>
<head>
    <title>{{ $produit->nom }}</title>
</head>
<body>

<h1>{{ $produit->nom }}</h1>

<img src="{{ $produit->image }}" width="300">

<p>{{ $produit->description }}</p>

<h3>Prix : {{ $produit->prix }} FCFA</h3>

<!-- 🛒 bouton panier -->
<a href="/panier/ajouter/{{ $produit->id }}">
    Ajouter au panier
</a>

<br><br>

<a href="/produits">← Retour</a>

</body>
</html>