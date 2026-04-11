<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .info {
            flex: 1;
            margin-left: 15px;
        }

        .actions a {
            margin: 0 5px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .plus { background: green; color: white; }
        .moins { background: orange; color: white; }
        .delete { background: red; color: white; }

        .total {
            text-align: right;
            font-size: 20px;
            margin-top: 20px;
        }

        .checkout {
            margin-top: 20px;
            padding: 15px;
            background: #007bff;
            color: white;
            border: none;
            width: 100%;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
        }

        .empty {
            text-align: center;
            padding: 50px;
            color: gray;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🛒 Mon Panier</h1>

    {{-- PANIER VIDE --}}
    @if(empty($cart))
        <div class="empty">
            Ton panier est vide 😢
        </div>
    @else

        {{-- PRODUITS --}}
        @foreach($cart as $item)
            <div class="item">

                <img src="{{ $item['image'] }}" alt="image">

                <div class="info">
                    <strong>{{ $item['nom'] }}</strong><br>
                    {{ $item['prix'] }} FCFA
                </div>

                <div>
                    Quantité: <strong>{{ $item['quantite'] }}</strong>
                </div>

                <div class="actions">
                    <a class="plus" href="/panier/update/{{ $item['id'] }}/plus">+</a>
                    <a class="moins" href="/panier/update/{{ $item['id'] }}/moins">-</a>
                    <a class="delete" href="/panier/supprimer/{{ $item['id'] }}">x</a>
                </div>

            </div>
        @endforeach

        {{-- TOTAL --}}
        <div class="total">
            Total : <strong>{{ $total }} FCFA</strong>
        </div>

        {{-- CHECKOUT --}}
        <form action="/checkout" method="POST">
            @csrf

            <input type="text" name="adresse" placeholder="Adresse de livraison" required>

            <button class="checkout" type="submit">
                Valider la commande
            </button>
        </form>

        <br>

        <a href="/panier/vider" style="color:red; display:block; text-align:center;">
            Vider le panier
        </a>

    @endif

</div>

</body>
</html>