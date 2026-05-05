@extends('layouts.admin')

@section('title', 'Gestion des Clients')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Base de données Clients</h3>
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
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $clientUser)
                    <tr>
                        <td><strong>{{ $clientUser->name }}</strong></td>
                        <td><a href="mailto:{{ $clientUser->email }}" style="color: #2563eb;">{{ $clientUser->email }}</a></td>
                        <td>{{ optional($clientUser->client)->telephone ?? '-' }}</td>
                        <td>{{ optional($clientUser->client)->ville ?? '-' }}</td>
                        <td>{{ $clientUser->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">Aucun client enregistré pour le moment.</td>
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
