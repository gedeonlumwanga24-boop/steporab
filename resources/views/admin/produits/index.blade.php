@extends('layouts.admin')

@section('title', 'Gestion des Produits')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Tous les produits</h3>
        <a href="{{ route('admin.produits.create') }}" class="btn-primary-sm">
            <i class="fa-solid fa-plus"></i> Nouveau Produit
        </a>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                    <tr>
                        <td>
                            @php
                                $imgUrl = $produit->image ? asset('storage/produits/'.$produit->image) : asset('images/2020-nike.jpg');
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $produit->nom }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                        </td>
                        <td><strong>{{ $produit->nom }}</strong></td>
                        <td>{{ $produit->category->nom ?? 'N/A' }}</td>
                        <td>{{ number_format($produit->prix, 0, ' ', ' ') }} CDF</td>
                        <td>
                            @if($produit->stock > 0)
                                <span class="badge badge-success">{{ $produit->stock }} en stock</span>
                            @else
                                <span class="badge badge-error">Rupture</span>
                            @endif
                        </td>
                        <td>
                            <div class="admin-action-links">
                                <a href="{{ route('admin.produits.edit', $produit->id) }}" class="btn-icon text-blue" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.produits.destroy', $produit->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-red" style="border: none; background: transparent; cursor: pointer;" title="Supprimer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($produits->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border);">
            {{ $produits->links() }}
        </div>
    @endif
</div>
@endsection
