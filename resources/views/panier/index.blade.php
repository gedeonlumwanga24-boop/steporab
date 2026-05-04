@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="mb-8">
        <h1 class="text-5xl font-bold mb-1">MON PANIER</h1>
        <p class="text-gray-500">{{ $cart ? count($cart) . ' ' . (count($cart) > 1 ? 'produits' : 'produit') : '0 produits' }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if(empty($cart))
        <div class="mt-12 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-16 text-center">
            <p class="text-lg text-gray-600 mb-6">Le panier est vide</p>
            <a href="{{ route('produits.index') }}" class="inline-block rounded-lg bg-black px-8 py-3 text-white font-semibold hover:bg-gray-900">
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
                        $itemImagePath = $item['image'] ?? null;
                        $itemImageUrl = asset('images/2020-nike.jpg');
                        if ($itemImagePath) {
                            $imagePath = preg_replace('#^(/|resources/images/|images/)#', '', $itemImagePath);
                            if (file_exists(public_path('storage/' . $imagePath))) {
                                $itemImageUrl = asset('storage/' . $imagePath);
                            } elseif (file_exists(public_path($imagePath))) {
                                $itemImageUrl = asset($imagePath);
                            } elseif (file_exists(public_path('images/' . $imagePath))) {
                                $itemImageUrl = asset('images/' . $imagePath);
                            } elseif (file_exists(resource_path('images/' . $imagePath))) {
                                $itemImageUrl = asset('images/' . $imagePath);
                            }
                        }
                        @endphp
                        
                        <img src="{{ $itemImageUrl }}" alt="{{ $item['nom'] }}" class="h-24 w-24 object-cover">
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item['nom'] }}</h3>
                            <p class="text-sm text-gray-600">{{ number_format($item['prix'], 0, ' ', ' ') }} CDF</p>
                            
                            <!-- Quantité -->
                            <div class="mt-3 flex items-center gap-3">
                                <a href="{{ route('panier.update', ['id' => $item['id'], 'action' => 'moins']) }}" class="h-8 w-8 border border-gray-300 flex items-center justify-center hover:bg-gray-100">−</a>
                                <span class="w-8 text-center font-medium">{{ $item['quantite'] }}</span>
                                <a href="{{ route('panier.update', ['id' => $item['id'], 'action' => 'plus']) }}" class="h-8 w-8 border border-gray-300 flex items-center justify-center hover:bg-gray-100">+</a>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ number_format($item['prix'] * $item['quantite'], 0, ' ', ' ') }} CDF</p>
                            <a href="{{ route('panier.supprimer', $item['id']) }}" class="text-sm text-gray-500 hover:text-red-600 mt-2 block">Supprimer</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- RÉSUMÉ ET LIVRAISON -->
            <div>
                <!-- RÉSUMÉ DE COMMANDE -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Résumé</h2>
                    
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total</span>
                            <span class="font-medium">{{ number_format($total, 0, ' ', ' ') }} CDF</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Livraison</span>
                            <span class="font-medium">Gratuite</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 text-lg font-bold">
                        <div class="flex justify-between">
                            <span>Total</span>
                            <span>{{ number_format($total, 0, ' ', ' ') }} CDF</span>
                        </div>
                    </div>
                </div>

                <!-- ADRESSE DE LIVRAISON -->
                <form action="{{ route('commande.store') }}" method="POST" class="bg-white border border-gray-200 rounded-lg p-6">
                    @csrf
                    <h2 class="text-lg font-semibold mb-4">Livraison</h2>
                    <textarea name="adresse" rows="4" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black" placeholder="Adresse complète"></textarea>
                    <button type="submit" class="w-full bg-black text-white font-semibold py-3 rounded-lg mt-4 hover:bg-gray-900">
                        Valider la commande
                    </button>
                </form>

                <a href="{{ route('panier.vider') }}" class="block text-center text-sm text-gray-600 hover:text-gray-900 mt-4 underline">
                    Vider le panier
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

