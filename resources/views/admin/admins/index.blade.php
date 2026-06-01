@extends('layouts.admin')

@section('title', 'Administrateurs')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <p style="color: #6b7280; margin: 0;">Gérez les comptes ayant accès au panel d'administration.</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="btn-primary-sm" style="display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem;">
        <i class="fa-solid fa-user-plus"></i> Nouvel administrateur
    </a>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">
            <i class="fa-solid fa-shield-halved" style="margin-right: 8px; color: #6366f1;"></i>
            Liste des administrateurs ({{ $admins->count() }})
        </h3>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Membre depuis</th>
                    <th>Statut</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.9rem; flex-shrink: 0;">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $admin->name }}</strong>
                                @if($admin->id === auth()->id())
                                    <span style="font-size: 0.7rem; background: #dbeafe; color: #1d4ed8; padding: 0.1rem 0.5rem; border-radius: 999px; margin-left: 6px; font-weight: 600;">Vous</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color: #6b7280;">{{ $admin->email }}</td>
                    <td style="color: #6b7280;">{{ $admin->created_at->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge badge-success">Actif</span>
                    </td>
                    <td style="text-align: center;">
                        @if($admin->id !== auth()->id())
                        <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir révoquer les droits admin de {{ addslashes($admin->name) }} ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon text-red" title="Révoquer les droits admin" style="background: none; border: none; cursor: pointer;">
                                <i class="fa-solid fa-user-slash"></i>
                            </button>
                        </form>
                        @else
                        <span style="color: #d1d5db; font-size: 0.8rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem; color: #9ca3af;">
                        <i class="fa-solid fa-user-slash" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        Aucun administrateur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
