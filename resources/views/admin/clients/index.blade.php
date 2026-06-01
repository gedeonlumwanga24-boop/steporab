@extends('layouts.admin')

@section('title', 'Gestion des Clients')

@section('content')
<div class="admin-card">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="admin-card-title">Base de données Clients</h3>
        <div>
            @if(request()->has('trashed'))
                <a href="{{ route('admin.clients.index') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; background: #000; color: #fff; text-decoration: none; border-radius: 4px;">Voir actifs</a>
            @else
                <a href="{{ route('admin.clients.index', ['trashed' => 1]) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; text-decoration: none; border-radius: 4px;">Voir supprimés</a>
            @endif
        </div>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom Complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Ville</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $clientUser)
                    <tr>
                        <td>
                            <strong>{{ $clientUser->name }}</strong>
                            @if($clientUser->trashed())
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.1rem 0.4rem; border-radius: 4px; font-size: 0.75rem; margin-left: 0.5rem;">Supprimé</span>
                            @endif
                        </td>
                        <td><a href="mailto:{{ $clientUser->email }}" style="color: #2563eb;">{{ $clientUser->email }}</a></td>
                        <td>{{ optional($clientUser->client)->telephone ?? '-' }}</td>
                        <td>{{ optional($clientUser->client)->ville ?? '-' }}</td>
                        <td>{{ $clientUser->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($clientUser->trashed())
                                <form action="{{ route('admin.clients.restore', $clientUser->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 0.25rem 0.5rem; background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; border-radius: 4px; cursor: pointer;">Restaurer</button>
                                </form>
                            @else
                                <form action="{{ route('admin.clients.destroy', $clientUser->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 4px; cursor: pointer;">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Aucun client trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($clients->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border);">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection
