@extends('layouts.app')

@section('title', 'Messagerie & Assistance')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-1">Messagerie</h1>
            <p class="text-gray-500">
                Contactez l'administration et suivez vos demandes
            </p>
        </div>
        <a href="{{ route('compte.show') }}" class="text-sm font-medium text-gray-600 hover:text-black flex items-center gap-2 bg-gray-100 px-4 py-2 rounded-lg">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Retour au compte
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800 font-medium">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-8 md:grid-cols-[1fr_300px] lg:grid-cols-[1fr_350px] items-start">
        
        {{-- LISTE DES MESSAGES --}}
        <div class="space-y-6">
            @if($messages->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-12 text-center">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" class="mx-auto mb-4"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <p class="text-lg text-gray-600 mb-2">Aucun message pour le moment.</p>
                    <p class="text-sm text-gray-500">Utilisez le formulaire pour nous contacter.</p>
                </div>
            @else
                @foreach($messages as $msg)
                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                            <div>
                                <h4 class="font-bold text-gray-900">Demande du {{ $msg->created_at->format('d/m/Y') }}</h4>
                                <span class="text-xs text-gray-500">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                            <div>
                                @if($msg->status === 'non lu')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">En attente</span>
                                @elseif($msg->status === 'lu')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">Vu par l'équipe</span>
                                @elseif($msg->status === 'répondu')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">Répondu</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-3 border border-gray-100">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $msg->message }}</p>
                        </div>
                        
                        @if($msg->reply)
                            <div class="bg-green-50 rounded-lg p-4 border border-green-200 relative">
                                <div class="absolute -top-2 left-6 w-4 h-4 bg-green-50 border-t border-l border-green-200 transform rotate-45"></div>
                                <div class="flex items-center gap-2 mb-2 relative z-10">
                                    <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">S</div>
                                    <span class="text-xs font-bold text-green-800">Support Stepora</span>
                                </div>
                                <p class="text-sm text-green-900 whitespace-pre-wrap relative z-10">{!! nl2br(e($msg->reply)) !!}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        {{-- FORMULAIRE NOUVEAU MESSAGE --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm sticky top-24">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Nouveau message</h3>
            
            <form action="{{ route('messagerie.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Comment pouvons-nous vous aider ?</label>
                    <textarea 
                        name="message" 
                        id="message" 
                        rows="5" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-black focus:border-black outline-none transition-shadow"
                        placeholder="Écrivez votre message ici..."
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-black text-white font-bold py-3 rounded-lg hover:bg-gray-900 transition-colors flex items-center justify-center gap-2">
                    Envoyer
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
