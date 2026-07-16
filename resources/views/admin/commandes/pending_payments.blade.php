@extends('layouts.admin')

@section('title', 'Paiements en attente')

@section('content')

@if(session('success'))
  <div style="background:#d1fae5;color:#065f46;padding:.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;font-weight:600;display:flex;align-items:center;gap:.5rem;">
    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div style="background:#fee2e2;color:#991b1b;padding:.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;font-weight:600;">
    {{ session('error') }}
  </div>
@endif
@if(session('info'))
  <div style="background:#eff6ff;color:#1e40af;padding:.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;font-weight:600;">
    {{ session('info') }}
  </div>
@endif

<div class="admin-card">
  <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;">
    <h3 class="admin-card-title">
      Paiements en attente de vérification
      <span style="display:inline-flex;align-items:center;justify-content:center;background:#dc2626;color:#fff;font-size:.75rem;font-weight:700;width:22px;height:22px;border-radius:50%;margin-left:.5rem;">
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
          <th>Mode de paiement</th>
          <th>Détails / Preuve</th>
          <th>Date</th>
          <th style="text-align:center;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($commandes as $commande)
        <tr>
          <td><strong>#{{ str_pad($commande->id, 5, '0', STR_PAD_LEFT) }}</strong></td>

          <td>
            {{ $commande->user->name ?? 'Inconnu' }}<br>
            <span style="font-size:.75rem;color:#6b7280;">{{ $commande->user->email ?? '' }}</span>
          </td>

          <td><strong>{{ number_format($commande->total, 0, ' ', ' ') }} CDF</strong></td>

          {{-- Colonne : mode de paiement --}}
          <td>
            @if($commande->pawapay_deposit_id)
              {{-- === PAWAPAY AUTOMATIQUE === --}}
              <div style="display:flex;align-items:center;gap:.5rem;">
                @if($commande->mobile_money_provider === 'VODACOM_MPESA_COD')
                  <img src="{{ asset('images/mpesa.png') }}" alt="M-Pesa" style="height:20px;object-fit:contain;">
                  <span style="font-weight:700;font-size:.85rem;">M-Pesa</span>
                @elseif($commande->mobile_money_provider === 'AIRTEL_COD')
                  <img src="{{ asset('images/airtel_money.png') }}" alt="Airtel" style="height:20px;object-fit:contain;">
                  <span style="font-weight:700;font-size:.85rem;">Airtel Money</span>
                @elseif($commande->mobile_money_provider === 'ORANGE_COD')
                  <img src="{{ asset('images/orange_money.png') }}" alt="Orange" style="height:20px;object-fit:contain;">
                  <span style="font-weight:700;font-size:.85rem;">Orange Money</span>
                @else
                  <span style="font-weight:700;font-size:.85rem;">Mobile Money</span>
                @endif
              </div>
              <span style="font-size:.72rem;background:#eff6ff;color:#1d4ed8;padding:.15rem .45rem;border-radius:4px;font-weight:600;display:inline-block;margin-top:.3rem;">PawaPay · Auto</span>
            @elseif($commande->payment_method)
              {{-- === ANCIEN SYSTÈME MANUEL === --}}
              <span style="font-size:.85rem;font-weight:600;">{{ $commande->payment_method_label }}</span>
              <span style="font-size:.72rem;background:#fef9c3;color:#92400e;padding:.15rem .45rem;border-radius:4px;font-weight:600;display:inline-block;margin-top:.3rem;">Paiement manuel</span>
            @else
              <span style="color:#9ca3af;font-size:.85rem;">—</span>
            @endif
          </td>

          {{-- Colonne : détails / preuve --}}
          <td>
            @if($commande->pawapay_deposit_id)
              {{-- PawaPay : afficher numéro + ID transaction --}}
              <div style="font-size:.82rem;">
                <div style="color:#374151;">+{{ $commande->mobile_money_number }}</div>
                <div style="font-family:monospace;font-size:.72rem;color:#94a3b8;margin-top:.2rem;">{{ substr($commande->pawapay_deposit_id, 0, 12) }}…</div>
              </div>
            @else
              {{-- Ancien système : afficher la preuve image + référence --}}
              @if($commande->payment_proof)
                <a href="{{ asset('storage/preuves/' . $commande->payment_proof) }}" target="_blank">
                  <img src="{{ asset('storage/preuves/' . $commande->payment_proof) }}"
                       alt="Preuve"
                       style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #e5e7eb;cursor:pointer;">
                </a>
              @else
                <span style="color:#9ca3af;font-size:.78rem;">Aucune preuve</span>
              @endif
              @if($commande->payment_reference)
                <div style="font-family:monospace;font-size:.72rem;color:#94a3b8;margin-top:.25rem;">Réf: {{ $commande->payment_reference }}</div>
              @endif
            @endif
          </td>

          <td style="font-size:.8rem;color:#6b7280;white-space:nowrap;">
            {{ $commande->created_at->format('d/m/Y H:i') }}
          </td>

          {{-- Actions --}}
          <td>
            <div style="display:flex;flex-direction:column;gap:.4rem;align-items:center;">

              @if($commande->pawapay_deposit_id)
                {{-- === Actions PawaPay === --}}
                <form action="{{ route('admin.commandes.sync-pawapay', $commande->id) }}" method="POST">
                  @csrf
                  <button type="submit"
                          style="background:#1d4ed8;color:#fff;border:none;border-radius:6px;padding:.4rem .85rem;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:.35rem;"
                          title="Vérifier le statut chez PawaPay">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Vérifier
                  </button>
                </form>
              @else
                {{-- === Actions paiement manuel === --}}
                <form action="{{ route('admin.commandes.valider', $commande->id) }}" method="POST"
                      onsubmit="return confirm('Valider ce paiement ?')">
                  @csrf
                  <button type="submit"
                          style="background:#10b981;color:#fff;border:none;border-radius:6px;padding:.4rem .85rem;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;">
                    Valider
                  </button>
                </form>
              @endif

              <form action="{{ route('admin.commandes.refuser', $commande->id) }}" method="POST"
                    onsubmit="return confirm('Refuser ce paiement ?')">
                @csrf
                <button type="submit"
                        style="background:#fff;color:#dc2626;border:1.5px solid #dc2626;border-radius:6px;padding:.35rem .85rem;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;">
                  Refuser
                </button>
              </form>

              <a href="{{ route('admin.commandes.show', $commande->id) }}"
                 style="color:#6b7280;font-size:.75rem;font-weight:500;text-decoration:none;">
                Détails
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" style="text-align:center;padding:3rem;color:#6b7280;">
            <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto .75rem;display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Aucun paiement en attente de vérification.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($commandes->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid var(--admin-border);">
      {{ $commandes->links() }}
    </div>
  @endif
</div>
@endsection
