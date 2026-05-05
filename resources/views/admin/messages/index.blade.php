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
                    <th>Expéditeur</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td style="white-space: nowrap;">{{ $message->created_at->format('d/m/Y H:i') }}</td>
                        <td style="white-space: nowrap;"><strong>{{ $message->nom }}</strong></td>
                        <td><a href="mailto:{{ $message->email }}" style="color: #2563eb;">{{ $message->email }}</a></td>
                        <td style="max-width: 400px;">
                            <div style="background: #f9fafb; padding: 0.75rem; border-radius: 6px; border: 1px solid var(--admin-border); font-size: 0.875rem; line-height: 1.5;">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Aucun message reçu pour le moment.</td>
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
