@extends('layouts.admin')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="admin-card" style="max-width: 700px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Modifier : {{ $category->nom }}</h3>
    </div>
    
    <div style="padding: 1.5rem;">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="admin-form-group">
                <label class="admin-label">Nom de la catégorie</label>
                <input type="text" name="nom" class="admin-input" value="{{ $category->nom }}" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Catégorie Parente (Optionnel)</label>
                <select name="parent_id" class="admin-input">
                    <option value="">-- Aucune (Catégorie principale) --</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Image de la collection</label>
                @if($category->image)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/categories/'.$category->image) }}" alt="" style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid var(--admin-border);">
                    </div>
                @endif
                <input type="file" name="image" class="admin-input" accept="image/*">
                <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">Laissez vide pour conserver l'image actuelle.</p>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border); text-align: right;">
                <button type="submit" class="btn-primary-sm" style="padding: 0.75rem 2rem; font-size: 1rem;">Mettre à jour la catégorie</button>
            </div>
        </form>
    </div>
</div>
@endsection
