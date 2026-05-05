@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Toutes les catégories</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary-sm">Nouvelle Catégorie</a>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Parent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td>
                        @if($category->image)
                            <img src="{{ asset('storage/categories/'.$category->image) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 0.7rem; color: #999;">Pas d'image</div>
                        @endif
                    </td>
                    <td style="font-weight: 600;">{{ $category->nom }}</td>
                    <td>{{ $category->parent ? $category->parent->nom : '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-edit" title="Modifier"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
