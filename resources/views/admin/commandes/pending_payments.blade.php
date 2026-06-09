@extends('layouts.admin')

@section('title', 'Paiements en attente')

@section('content')

@if(session('success'))
    <div style="background: #d1fae5; color: #065f46; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-weight: 600;">
        {{ session('error') }}
    </div>
@endif

<div class="admin-card">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="admin-card-title">
            Commandes en attente de paiement
            <span style="display: inline-flex; align-items: center; justify-content: center; background: #dc2626; color: #fff; font-size: 0.75rem; font-weight: 700; width: 22px; height: 22px; border-radius: 50%; margin-left: 0.5rem;">
                {{ $commandes->total() }}
            </span>
        </h3>
        <a href="{{ route('admin.commandes.index') }}" class="btn-visit-site">
            <i class="fa-solid fa-arrow-left"></i> Toutes les commandes
        </a>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Mode</th>
                    <th>Téléphone</th>
                    <th>Référence</th>
                    <th>Preuve</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                <tr>
                    <td><strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>
                        {{ $commande->user->name ?? 'Inconnu' }}<br>
                        <span style="font-size: 0.75rem; color: #6b7280;">{{ $commande->user->email ?? '' }}</span>
                    </td>
                    <td><strong>{{ number_format($commande->total, 0, ' ', ' ') }} CDF</strong></td>
                    <td>
                        @if($commande->payment_method === 'mpesa')
                            <span style="background: #d1fae5; color: #065f46; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">M-Pesa</span>
                        @elseif($commande->payment_method === 'orange_money')
                            <span style="background: #ffedd5; color: #c2410c; padding: 0.2rem 0.6rem; border-radius: 6px; font-size: 0.78rem; font-weight: 700;">Orange Money</span>
                        @else
                            <span style="color: #9ca3af; font-size: 0.78rem;">—</span>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem;">{{ $commande->payment_phone ?? '—' }}</td>
                    <td style="font-size: 0.85rem; font-family: monospace;">{{ $commande->payment_reference ?? '—' }}</td>
                    <td>
                        @if($commande->payment_proof)
                            <a href="{{ asset('storage/preuves/' . $commande->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/preuves/' . $commande->payment_proof) }}"
                                     alt="Preuve"
                                     style="width: 52px; height: 52px; object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb; cursor: pointer; transition: transform 0.2s;"
                                     onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        @else
                            <span style="color: #9ca3af; font-size: 0.78rem;">Aucune</span>
                        @endif
                    </td>
                    <td style="font-size: 0.8rem; color: #6b7280; white-space: nowrap;">{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: nowrap;">
                            {{-- Valider --}}
                            <form action="{{ route('admin.commandes.valider', $commande->id) }}" method="POST"
                                  onsubmit="return confirm('Valider ce paiement ?')">
                                @csrf
                                <button type="submit"
                                        style="background: #10b981; color: #fff; border: none; border-radius: 6px; padding: 0.4rem 0.85rem; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: background 0.15s;"
                                        onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                    Valider
                                </button>
                            </form>
                            {{-- Refuser --}}
                            <form action="{{ route('admin.commandes.refuser', $commande->id) }}" method="POST"
                                  onsubmit="return confirm('Refuser ce paiement ?')">
                                @csrf
                                <button type="submit"
                                        style="background: #fff; color: #dc2626; border: 1.5px solid #dc2626; border-radius: 6px; padding: 0.4rem 0.85rem; font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: all 0.15s;"
                                        onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='#fff'">
                                    Refuser
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem; color: #6b7280;">
                        ✓ Aucune commande en attente de validation.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($commandes->hasPages())
        <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border);">
            {{ $commandes->links() }}
        </div>
    @endif
</div>
@endsection
