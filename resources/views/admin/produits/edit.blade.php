@extends('layouts.admin')

@section('title', 'Modifier le Produit')

@section('content')
<div class="admin-card" style="max-width: 800px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Modification : {{ $produit->nom }}</h3>
        <a href="{{ route('admin.produits.index') }}" class="btn-visit-site">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <div style="padding: 1.5rem;">
        <form action="{{ route('admin.produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="admin-form-group">
                <label class="admin-label">Nom du produit</label>
                <input type="text" name="nom" class="admin-input" value="{{ old('nom', $produit->nom) }}" required>
                @error('nom') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="admin-form-group">
                    <label class="admin-label">Catégorie</label>
                    <select name="category_id" class="admin-select" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $produit->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Prix (CDF)</label>
                    <input type="number" name="prix" class="admin-input" value="{{ old('prix', $produit->prix) }}" min="0" required>
                    @error('prix') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Stock disponible</label>
                <input type="number" name="stock" class="admin-input" value="{{ old('stock', $produit->stock) }}" min="0" required style="width: 200px;">
                @error('stock') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Description</label>
                <textarea name="description" class="admin-textarea" rows="5" required>{{ old('description', $produit->description) }}</textarea>
                @error('description') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Image principale (Laisser vide pour conserver l'actuelle)</label>
                
                @if($produit->image)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/produits/'.$produit->image) }}" alt="{{ $produit->nom }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid var(--admin-border);">
                    </div>
                @endif
                
                <input type="file" name="image" class="admin-input" accept="image/*">
                @error('image') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Miniatures / Galerie (Écrase la galerie actuelle)</label>
                
                @if($produit->galerie && count($produit->galerie) > 0)
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                        @foreach($produit->galerie as $img)
                            <img src="{{ asset('storage/produits/'.$img) }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);">
                        @endforeach
                    </div>
                @endif

                <input type="file" name="galerie[]" class="admin-input" accept="image/*" multiple>
                <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">Sélectionnez de nouvelles miniatures pour remplacer les anciennes.</p>
                @error('galerie.*') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: right;">
                <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 1rem;">Mettre à jour le produit</button>
            </div>
        </form>
    </div>
</div>
@endsection
