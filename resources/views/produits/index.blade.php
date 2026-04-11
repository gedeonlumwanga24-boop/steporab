<!DOCTYPE html>
<html>
<head>
    <title>Produits</title>
</head>
<body>

<h1>🛍️ Nos Produits</h1>

<div style="display:flex; flex-wrap:wrap; gap:20px;">

@foreach($produits as $produit)

    <div style="border:1px solid #ddd; padding:10px; width:200px;">

        <img src="{{ $produit->image }}" width="100%" />

        <h3>{{ $produit->nom }}</h3>

        <p>{{ $produit->prix }} FCFA</p>

        <!--  lien vers page produit -->
        <a href="/produits/{{ $produit->id }}">Voir</a>

        <br><br>

        <!-- 🛒 ajouter au panier -->
        <a href="/panier/ajouter/{{ $produit->id }}">Ajouter</a>

    </div>

@endforeach

</div>

</body>
</html>