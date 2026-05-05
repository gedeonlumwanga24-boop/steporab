@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    
    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-5xl font-bold mb-1">MON PANIER</h1>
        <p class="text-gray-500">
            {{ $cart ? count($cart) . ' ' . (count($cart) > 1 ? 'produits' : 'produit') : '0 produits' }}
        </p>
    </div>

    <!-- ALERT SUCCESS -->
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- ALERT ERROR -->
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- PANIER VIDE -->
    @if(!$cart || count($cart) === 0)
        <div class="mt-12 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-16 text-center">
            <p class="text-lg text-gray-600 mb-6">Le panier est vide</p>
            <a href="{{ route('produits.index') }}" 
               class="inline-block rounded-lg bg-black px-8 py-3 text-white font-semibold hover:bg-gray-900">
                C'est parti
            </a>
        </div>
    @else

    <div class="grid gap-8 lg:grid-cols-[1fr_350px]">

        <!-- PRODUITS -->
        <div class="space-y-4">
            @foreach($cart as $item)
                <div class="flex gap-4 border-b border-gray-200 pb-6">
                    
                    @php
                        $itemImageUrl = asset('images/2020-nike.jpg');

                        if (!empty($item['image'])) {
                            $itemImageUrl = asset('storage/produits/' . $item['image']);
                        }
                    @endphp

                    
                    <!-- IMAGE -->
                    <img src="{{ $itemImageUrl }}" 
                         alt="{{ $item['nom'] }}" 
                         class="h-24 w-24 object-cover rounded">

                    <!-- INFOS -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item['nom'] }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ number_format($item['prix'], 0, ' ', ' ') }} CDF
                        </p>

                        <!-- QUANTITÉ -->
                        <div class="mt-3 flex items-center gap-3">
                            <a href="{{ route('panier.update', ['id' => $item['id'], 'action' => 'moins']) }}"
                               class="h-8 w-8 border flex items-center justify-center hover:bg-gray-100">
                                −
                            </a>

                            <span class="w-8 text-center font-medium">
                                {{ $item['quantite'] }}
                            </span>

                            <a href="{{ route('panier.update', ['id' => $item['id'], 'action' => 'plus']) }}"
                               class="h-8 w-8 border flex items-center justify-center hover:bg-gray-100">
                                +
                            </a>
                        </div>
                    </div>

                    <!-- TOTAL + DELETE -->
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">
                            {{ number_format($item['prix'] * $item['quantite'], 0, ' ', ' ') }} CDF
                        </p>

                        <a href="{{ route('panier.supprimer', $item['id']) }}"
                           class="text-sm text-gray-500 hover:text-red-600 mt-2 block">
                            Supprimer
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- SIDEBAR -->
        <div>

            <!-- RÉSUMÉ -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Résumé</h2>

                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total</span>
                        <span class="font-medium">
                            {{ number_format($total, 0, ' ', ' ') }} CDF
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Livraison</span>
                        <span class="font-medium">Gratuite</span>
                    </div>
                </div>

                <div class="border-t pt-4 text-lg font-bold flex justify-between">
                    <span>Total</span>
                    <span>{{ number_format($total, 0, ' ', ' ') }} CDF</span>
                </div>
            </div>

            <!-- LIVRAISON -->
            @if(Auth::check())
            <form action="{{ route('commande.store') }}" method="POST"
                  class="bg-white border border-gray-200 rounded-lg p-6">
                @csrf

                <h2 class="text-lg font-semibold mb-4">Informations de livraison</h2>

                <div class="space-y-3">
                    <input type="text" name="name" value="{{ Auth::user()->name }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black"
                           placeholder="Nom complet">
                           
                    <input type="text" name="telephone" value="{{ optional(Auth::user()->client)->telephone }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black"
                           placeholder="Téléphone (+243...)">

                    <input type="text" name="ville" value="{{ optional(Auth::user()->client)->ville }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black"
                           placeholder="Ville">

                    <textarea name="adresse" rows="3" required
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black"
                              placeholder="Adresse complète (Avenue, Quartier, Commune)">{{ optional(Auth::user()->client)->adresse }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-black text-white font-semibold py-3 rounded-lg mt-4 hover:bg-gray-900">
                    Valider la commande
                </button>
            </form>
            @else
            <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
                <h2 class="text-lg font-semibold mb-4">Livraison</h2>
                <p class="text-sm text-gray-600 mb-4">Veuillez vous connecter pour renseigner vos informations de livraison et passer commande.</p>
                <a href="{{ route('login') }}" class="inline-block w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-900">
                    Se connecter
                </a>
            </div>
            @endif

            <!-- VIDER PANIER -->
            <a href="{{ route('panier.vider') }}"
               class="block text-center text-sm text-gray-600 hover:text-gray-900 mt-4 underline">
                Vider le panier
            </a>

        </div>
    </div>

    @endif
</div>
@endsection