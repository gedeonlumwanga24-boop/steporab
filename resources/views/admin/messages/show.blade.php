@extends('layouts.admin')

@section('title', 'Consulter le message')

@section('content')
<div class="admin-card" style="max-width: 900px;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="admin-card-title">Message de {{ $message->nom }}</h3>
        <a href="{{ route('admin.messages.index') }}" class="btn-visit-site" style="text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div style="margin: 1rem 1.5rem; padding: 0.75rem 1rem; background: #ecfdf5; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="padding: 1.5rem;">
        <div style="margin-bottom: 2rem;">
            <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">Reçu le : {{ $message->created_at->format('d/m/Y à H:i') }}</p>
            <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">Email : <a href="mailto:{{ $message->email }}" style="color: #2563eb;">{{ $message->email }}</a></p>
            <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">Statut : 
                @if($message->status === 'non lu')
                    <span style="color: #991b1b; font-weight: bold;">Non lu</span>
                @elseif($message->status === 'lu')
                    <span style="color: #854d0e; font-weight: bold;">Lu</span>
                @elseif($message->status === 'répondu')
                    <span style="color: #166534; font-weight: bold;">Répondu</span>
                @endif
            </p>
        </div>

        <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--admin-border); margin-bottom: 2rem;">
            <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 1rem;">Contenu du message :</h4>
            <p style="margin: 0; line-height: 1.6;">{!! nl2br(e($message->message)) !!}</p>
        </div>

        <hr style="border: none; border-top: 1px solid var(--admin-border); margin: 2rem 0;">

        @if($message->status === 'répondu')
            <div style="background: #f0fdf4; padding: 1.5rem; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 2rem;">
                <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 1rem; color: #166534;">Votre réponse :</h4>
                <p style="margin: 0; line-height: 1.6; color: #166534;">{!! nl2br(e($message->reply)) !!}</p>
            </div>
        @else
            <div style="margin-bottom: 2rem;">
                <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 1rem;">Répondre au client</h4>
                @if ($errors->any())
                    <div style="background: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <ul style="margin: 0; padding-left: 1rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <textarea name="reply" rows="6" style="width: 100%; padding: 0.75rem; border: 1px solid var(--admin-border); border-radius: 6px; font-family: inherit; resize: vertical;" placeholder="Rédigez votre réponse ici. Un email sera envoyé au client." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; background: #000; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Envoyer la réponse</button>
                </form>
            </div>
        @endif

        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Supprimer ce message définitivement ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-icon text-red" style="border: none; background: transparent; cursor: pointer; color: #dc2626; padding: 0;">
                <i class="fa-solid fa-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>
@endsection
