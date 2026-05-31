@extends('layouts.admin')

@section('title', 'Gerer la requete')

@section('content')
<div class="admin-card" style="max-width: 900px;">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Requete de {{ $message->nom }}</h3>
        <a href="{{ route('admin.messages.index') }}" class="btn-visit-site">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div style="margin: 1rem 1.5rem; padding: 0.75rem 1rem; background: #ecfdf5; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding: 1.5rem;">
        <div style="display: grid; gap: 1rem; margin-bottom: 1.5rem;">
            <p><strong>Email :</strong> <a href="mailto:{{ $message->email }}" style="color: #2563eb;">{{ $message->email }}</a></p>
            <p><strong>Sujet :</strong> {{ $message->sujet }}</p>
            <p><strong>Date :</strong> {{ $message->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Type :</strong> {{ $message->user ? 'Client inscrit' : 'Visiteur' }}</p>
        </div>

        <div style="background: #f9fafb; border: 1px solid var(--admin-border); border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; line-height: 1.6;">
            {!! nl2br(e($message->message)) !!}
        </div>

        <form action="{{ route('admin.messages.update', $message) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="admin-form-group">
                <label class="admin-label">Statut</label>
                <select name="statut" class="admin-select">
                    <option value="nouveau" {{ $message->statut === 'nouveau' ? 'selected' : '' }}>Nouveau</option>
                    <option value="en_cours" {{ $message->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="traite" {{ $message->statut === 'traite' ? 'selected' : '' }}>Traite</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Notes admin</label>
                <textarea name="admin_notes" class="admin-textarea" rows="5" placeholder="Suivi interne, action faite, reponse envoyee...">{{ old('admin_notes', $message->admin_notes) }}</textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Reponse au client</label>
                <textarea name="admin_response" class="admin-textarea" rows="6" placeholder="Cette reponse sera visible par le client dans son espace compte.">{{ old('admin_response', $message->admin_response) }}</textarea>
                @if($message->responded_at)
                    <p style="font-size: 0.8rem; color: #6b7280; margin-top: 0.35rem;">
                        Derniere reponse envoyee le {{ $message->responded_at->format('d/m/Y H:i') }}.
                        {{ $message->response_read_at ? 'Lue par le client.' : 'Pas encore lue par le client.' }}
                    </p>
                @endif
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-primary-sm" style="border: none; cursor: pointer;">Mettre a jour</button>
            </div>
        </form>

        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Supprimer cette requete ?');" style="margin-top: 1rem;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-icon text-red" style="border: none; background: transparent; cursor: pointer;">Supprimer</button>
        </form>
    </div>
</div>
@endsection
