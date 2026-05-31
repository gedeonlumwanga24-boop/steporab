@extends('layouts.admin')

@section('title', 'Détails de l\'Erreur')

@section('content')
<div class="admin-header-actions" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.errors.index') }}" class="btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Retour aux erreurs
    </a>
    
    <div style="display: flex; gap: 1rem;">
        @if($error->status === 'en_attente')
            <form action="{{ route('admin.errors.update', $error) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="resolu">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check"></i> Marquer comme résolue
                </button>
            </form>
        @else
            <form action="{{ route('admin.errors.update', $error) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="en_attente">
                <button type="submit" class="btn btn-outline">
                    <i class="fa-solid fa-clock-rotate-left"></i> Remettre en attente
                </button>
            </form>
        @endif
        
        <form action="{{ route('admin.errors.destroy', $error) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #ef4444; color: white;">
                <i class="fa-solid fa-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h3 class="admin-card-title" style="color: #ef4444; font-family: monospace; font-size: 1.25rem;">
                {{ $error->message }}
            </h3>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.5rem;">
                Survenu le {{ $error->created_at->format('d/m/Y à H:i:s') }}
            </p>
        </div>
        <div>
            @if($error->status === 'en_attente')
                <span class="badge badge-error" style="font-size: 1rem; padding: 0.5rem 1rem;">En attente</span>
            @else
                <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">Résolue</span>
            @endif
        </div>
    </div>
    
    <div style="padding: 1.5rem;">
        <h4 style="margin-bottom: 1rem; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Contexte de la requête</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                <strong style="color: #6b7280; font-size: 0.8rem; text-transform: uppercase;">URL</strong>
                <div style="margin-top: 0.25rem; word-break: break-all;">
                    <span class="badge" style="background: #e5e7eb; color: #374151;">{{ $error->method }}</span> 
                    <a href="{{ $error->url }}" target="_blank" style="color: #2563eb;">{{ $error->url }}</a>
                </div>
            </div>
            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                <strong style="color: #6b7280; font-size: 0.8rem; text-transform: uppercase;">Adresse IP Client</strong>
                <div style="margin-top: 0.25rem; font-family: monospace; font-size: 1.1rem;">
                    {{ $error->ip_address }}
                </div>
            </div>
        </div>

        <h4 style="margin-bottom: 1rem; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Stack Trace</h4>
        <div style="background: #111827; color: #e5e7eb; padding: 1.5rem; border-radius: 8px; overflow-x: auto;">
            <pre style="font-family: monospace; font-size: 0.85rem; line-height: 1.6;">{{ $error->stack_trace ?: 'Aucune stack trace disponible.' }}</pre>
        </div>
    </div>
</div>

<style>
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-outline {
        border: 1px solid #d1d5db;
        background: transparent;
        color: #374151;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-outline:hover {
        background: #f3f4f6;
    }
    .btn-success {
        background: #10b981;
        color: white;
    }
</style>
@endsection
