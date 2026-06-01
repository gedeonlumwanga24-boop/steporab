@extends('layouts.admin')

@section('title', 'Boîte de réception (Messages)')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">Messages de contact</h3>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Expéditeur</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr style="{{ $message->status === 'non lu' ? 'background-color: #f0f9ff;' : '' }}">
                        <td style="white-space: nowrap;">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($message->status === 'non lu')
                                <span style="background: #fee2e2; color: #991b1b; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Non lu</span>
                            @elseif($message->status === 'lu')
                                <span style="background: #fef9c3; color: #854d0e; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Lu</span>
                            @elseif($message->status === 'répondu')
                                <span style="background: #dcfce7; color: #166534; padding: 0.1rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Répondu</span>
                            @endif
                        </td>
                        <td style="white-space: nowrap;"><strong>{{ $message->nom }}</strong></td>
                        <td><a href="mailto:{{ $message->email }}" style="color: #2563eb;">{{ $message->email }}</a></td>
                        <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ Str::limit($message->message, 50) }}
                        </td>
                        <td>
                            <a href="{{ route('admin.messages.show', $message) }}" class="btn btn-primary" style="padding: 0.25rem 0.5rem; background: #000; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.8rem;">Consulter</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Aucun message reçu pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($messages->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border);">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
