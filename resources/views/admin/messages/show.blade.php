@extends('layouts.admin')

@section('title', 'Conversation')

@section('content')
<div class="admin-card" style="max-width: 900px; display: flex; flex-direction: column; height: 80vh;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem; border-bottom: 1px solid var(--admin-border);">
        <div>
            <h3 class="admin-card-title" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-user-circle text-gray-400" style="font-size: 1.5rem;"></i>
                Conversation avec {{ $email }}
            </h3>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="btn-visit-site" style="text-decoration: none; padding: 0.5rem 1rem;">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div style="margin: 1rem 1.5rem 0; padding: 0.75rem 1rem; background: #ecfdf5; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- ZONE DE CHAT --}}
    <div style="flex: 1; overflow-y: auto; padding: 1.5rem; background: #f9fafb; display: flex; flex-direction: column; gap: 1rem;" id="chatContainer">
        @foreach($thread as $msg)
            @if($msg->is_admin)
                {{-- Bulle Admin (droite) --}}
                <div style="align-self: flex-end; max-width: 75%;">
                    <div style="background: #111; color: #fff; padding: 1rem 1.25rem; border-radius: 1.25rem 1.25rem 0 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                        <p style="margin: 0; line-height: 1.5; font-size: 0.95rem; white-space: pre-wrap;">{{ $msg->message }}</p>
                    </div>
                    <div style="text-align: right; margin-top: 0.25rem;">
                        <span style="font-size: 0.7rem; color: #6b7280; font-weight: 500;">Support Stepora • {{ $msg->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            @else
                {{-- Bulle Client (gauche) --}}
                <div style="align-self: flex-start; max-width: 75%;">
                    <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-bottom: 0.25rem;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #374151;">{{ $msg->nom }}</span>
                    </div>
                    <div style="background: #fff; color: #111; border: 1px solid #e5e7eb; padding: 1rem 1.25rem; border-radius: 1.25rem 1.25rem 1.25rem 0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <p style="margin: 0; line-height: 1.5; font-size: 0.95rem; white-space: pre-wrap;">{{ $msg->message }}</p>
                    </div>
                    <div style="margin-top: 0.25rem;">
                        <span style="font-size: 0.7rem; color: #6b7280; font-weight: 500;">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- ZONE DE SAISIE --}}
    <div style="padding: 1.5rem; border-top: 1px solid var(--admin-border); background: #fff;">
        <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
            @csrf
            @if ($errors->any())
                <div style="background: #fef2f2; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.85rem;">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div style="display: flex; gap: 1rem; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="reply" style="display: block; font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">Nouveau message :</label>
                    <textarea name="reply" id="reply" rows="3" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e5e7eb; border-radius: 10px; font-family: inherit; resize: none; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#111'" onblur="this.style.borderColor='#e5e7eb'" placeholder="Écrivez votre réponse ici..." required></textarea>
                </div>
                <button type="submit" style="background: #111; color: #fff; border: none; border-radius: 10px; padding: 0.75rem 1.5rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; height: 3rem; transition: background 0.2s;" onmouseover="this.style.background='#000'" onmouseout="this.style.background='#111'">
                    Envoyer <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; color: #9ca3af;">Le client recevra un email de notification.</p>
        </form>
    </div>
</div>

<script>
    // Scroll automatiquement en bas du chat au chargement
    const chatContainer = document.getElementById('chatContainer');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection
