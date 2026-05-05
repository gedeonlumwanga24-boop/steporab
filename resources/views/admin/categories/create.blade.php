@extends('layouts.admin')

@section('title', 'Ajouter une Catégorie')

@section('content')
<div class="admin-card" style="max-width: 700px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Nouvelle Catégorie</h3>
    </div>
    
    <div style="padding: 1.5rem;">
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="admin-form-group">
                <label class="admin-label">Nom de la catégorie</label>
                <input type="text" name="nom" class="admin-input" placeholder="Ex: Running" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Catégorie Parente (Optionnel)</label>
                <select name="parent_id" class="admin-input">
                    <option value="">-- Aucune (Catégorie principale) --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Image de la collection (S'affiche sur l'accueil)</label>
                <input type="file" name="image" class="admin-input" accept="image/*">
                <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">Format recommandé: Portrait ou Carré, haute qualité.</p>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: right;">
                <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 1rem;">Enregistrer la catégorie</button>
            </div>
        </form>
    </div>
</div>
@endsection
