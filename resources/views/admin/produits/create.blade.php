@extends('layouts.admin')

@section('title', 'Ajouter un Produit')

@section('content')
<div class="admin-card" style="max-width: 800px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Détails du nouveau produit</h3>
        <a href="{{ route('admin.produits.index') }}" class="btn-visit-site">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>
    
    <div style="padding: 1.5rem;">
        <form action="{{ route('admin.produits.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="admin-form-group">
                <label class="admin-label">Nom du produit</label>
                <input type="text" name="nom" class="admin-input" value="{{ old('nom') }}" required>
                @error('nom') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="admin-form-group">
                    <label class="admin-label">Catégorie</label>
                    <select name="category_id" class="admin-select" required>
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Prix (CDF)</label>
                    <input type="number" name="prix" class="admin-input" value="{{ old('prix') }}" min="0" required>
                    @error('prix') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Stock disponible</label>
                <input type="number" name="stock" class="admin-input" value="{{ old('stock', 0) }}" min="0" required style="width: 200px;">
                @error('stock') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Description</label>
                <textarea name="description" class="admin-textarea" rows="5" required>{{ old('description') }}</textarea>
                @error('description') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Image principale</label>
                <input type="file" name="image" class="admin-input" accept="image/*" required>
                @error('image') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Miniatures (Galerie d'images)</label>
                <input type="file" name="galerie[]" class="admin-input" accept="image/*" multiple>
                <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">Sélectionnez plusieurs images pour accompagner le produit. Max 2MB par image.</p>
                @error('galerie.*') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: right;">
                <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 1rem;">Enregistrer le produit</button>
            </div>
        </form>
    </div>
</div>
@endsection
