@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="bg-white rounded-[32px] p-6 shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold">🛒 Mon panier</h1>
                <p class="text-sm text-gray-500">Revérifie tes produits avant de valider la commande.</p>
            </div>
            <a href="{{ route('produits.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-slate-50 px-5 py-3 text-sm font-medium text-slate-900 hover:bg-slate-100">
                Continuer mes achats
            </a>
        </div>

        @if(session('success'))
            <div class="mt-6 rounded-3xl border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if(empty($cart))
            <div class="mt-8 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center text-slate-600">
                Ton panier est vide 😢<br>
                <a href="{{ route('produits.index') }}" class="mt-4 inline-flex rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                    Voir les produits
                </a>
            </div>
        @else
            <div class="mt-8 overflow-hidden rounded-[28px] border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 bg-white text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Produit</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Prix</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Quantité</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Sous-total</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($cart as $item)
                            <tr>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-center gap-4">
                                        @php
                                        $itemImagePath = $item['image'] ?? null;
                                        $itemImageUrl = asset('images/2020-nike.jpg');
                                        if ($itemImagePath) {
                                            if (file_exists(public_path('storage/'.$itemImagePath))) {
                                                $itemImageUrl = asset('storage/'.$itemImagePath);
                                            } elseif (file_exists(public_path('images/'.$itemImagePath))) {
                                                $itemImageUrl = asset('images/'.$itemImagePath);
                                            }
                                        }
                                    @endphp
                                    <img src="{{ $itemImageUrl }}" alt="{{ $item['nom'] }}" class="h-20 w-20 rounded-3xl object-cover border border-slate-200">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $item['nom'] }}</p>
                                            <p class="text-sm text-slate-500">{{ number_format($item['prix'], 0, ' ', ' ') }} FCFA</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-slate-700">{{ number_format($item['prix'], 0, ' ', ' ') }} FCFA</td>
                                <td class="px-6 py-5">
                                    <div class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2">
                                        <a href="{{ route('panier.update', ['id' => $item['id'], 'action' => 'moins']) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700 shadow-sm hover:bg-slate-100">-</a>
                                        <span class="w-10 text-center font-medium text-slate-900">{{ $item['quantite'] }}</span>
                                        <a href="{{ route('panier.update', ['id' => $item['id'], 'action' => 'plus']) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-slate-700 shadow-sm hover:bg-slate-100">+</a>
                                    </div>
                                </td>
                                <td class="px-6 py-5 font-semibold text-slate-900">{{ number_format($item['prix'] * $item['quantite'], 0, ' ', ' ') }} FCFA</td>
                                <td class="px-6 py-5">
                                    <a href="{{ route('panier.supprimer', $item['id']) }}" class="inline-flex rounded-2xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Supprimer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_320px]">
                <div class="rounded-[28px] border border-slate-200 bg-slate-50 p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Adresse de livraison</h2>
                    <form action="{{ route('commande.store') }}" method="POST" class="mt-4 space-y-4">
                        @csrf
                        <textarea name="adresse" rows="4" required class="w-full rounded-3xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none" placeholder="Adresse complète"></textarea>
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                            Valider la commande
                        </button>
                    </form>
                </div>

                <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500">Résumé</p>
                    <div class="mt-4 flex items-center justify-between text-base text-slate-700">
                        <span>Sous-total</span>
                        <span>{{ number_format($total, 0, ' ', ' ') }} FCFA</span>
                    </div>
                    <div class="mt-4 border-t border-slate-200 pt-4 text-xl font-semibold text-slate-900">
                        Total : {{ number_format($total, 0, ' ', ' ') }} FCFA
                    </div>
                    <a href="{{ route('panier.vider') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-3xl border border-red-600 px-6 py-3 text-sm font-semibold text-red-600 hover:bg-red-50">
                        Vider le panier
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

