@extends('layouts.admin')

@section('title', 'Gestion des Erreurs')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Journal des erreurs critiques</h3>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('admin.errors.index', ['status' => 'en_attente']) }}" class="btn {{ $status == 'en_attente' ? 'btn-primary' : 'btn-outline' }}">
                En attente
            </a>
            <a href="{{ route('admin.errors.index', ['status' => 'resolu']) }}" class="btn {{ $status == 'resolu' ? 'btn-success' : 'btn-outline' }}">
                Résolues
            </a>
        </div>
    </div>
    
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Message</th>
                    <th>URL</th>
                    <th>IP</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($errors as $error)
                    <tr>
                        <td style="white-space: nowrap;">{{ $error->created_at->format('d/m/Y H:i:s') }}</td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <strong>{{ Str::limit($error->message, 80) }}</strong>
                        </td>
                        <td><span style="font-size: 0.85em; color: #666;">{{ $error->method }}</span> {{ Str::limit($error->url, 40) }}</td>
                        <td>{{ $error->ip_address }}</td>
                        <td>
                            @if($error->status === 'en_attente')
                                <span class="badge badge-error">En attente</span>
                            @else
                                <span class="badge badge-success">Résolu</span>
                            @endif
                        </td>
                        <td style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.errors.show', $error) }}" class="btn-icon text-blue" title="Détails">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.errors.destroy', $error) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce log ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon text-red" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #666;">
                            <i class="fa-solid fa-check-circle" style="font-size: 2rem; color: #10b981; margin-bottom: 1rem; display: block;"></i>
                            Aucune erreur {{ str_replace('_', ' ', $status) }} trouvée. Tout fonctionne parfaitement !
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($errors->hasPages())
        <div style="padding: 1rem;">
            {{ $errors->withQueryString()->links() }}
        </div>
    @endif
</div>

<style>
    .btn-outline {
        border: 1px solid #d1d5db;
        background: transparent;
        color: #374151;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-outline:hover {
        background: #f3f4f6;
    }
    .btn-success {
        background: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #10b981;
    }
    .btn-primary {
        background: #2563eb;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        border: 1px solid #2563eb;
    }
</style>
@endsection
