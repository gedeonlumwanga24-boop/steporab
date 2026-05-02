@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
        <div class="bg-white rounded-3xl p-6 shadow-lg">
            <div class="overflow-hidden rounded-3xl mb-6">
                <img src="{{ asset('storage/'.$produit->image) }}" alt="{{ $produit->nom }}"
                     class="w-full h-full object-cover">
            </div>

            <div class="space-y-4">
                <h1 class="text-3xl font-semibold">{{ $produit->nom }}</h1>
                <p class="text-sm uppercase tracking-[0.25em] text-gray-500">{{ $produit->category->nom ?? 'Catégorie' }}</p>
                <p class="text-2xl font-bold text-red-600">{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</p>
                <p class="text-gray-700 leading-relaxed">{{ $produit->description }}</p>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Détails du produit</h2>
                <div class="grid gap-3 text-sm text-gray-700">
                    <div class="flex justify-between border-b border-gray-200 pb-3">
                        <span>Prix</span>
                        <strong>{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</strong>
                    </div>
                    <div class="flex justify-between border-b border-gray-200 pb-3">
                        <span>Catégorie</span>
                        <span>{{ $produit->category->nom ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Stock</span>
                        <span>{{ $produit->stock }}</span>
                    </div>
                </div>

                <form action="{{ route('panier.ajouter', $produit->id) }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="btn-cart w-full">Ajouter au panier</button>
                </form>
            </div>

            <a href="{{ route('produits.index') }}" class="block rounded-2xl border border-gray-200 bg-white px-5 py-4 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                ← Retour aux produits
            </a>
        </aside>
    </div>
</div>
@endsection
