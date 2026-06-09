@extends('layouts.app')

@section('title', 'Messagerie & Assistance')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">

    <div class="mb-8 flex items-center justify-between border-b pb-4 border-gray-200">
        <div>
            <h1 class="text-3xl font-extrabold mb-1 tracking-tight text-gray-900">Messagerie</h1>
            <p class="text-gray-500 font-medium">
                Discutez avec l'administration et suivez vos demandes en temps réel.
            </p>
        </div>
        <a href="{{ route('compte.show') }}" class="text-sm font-semibold text-gray-600 hover:text-black flex items-center gap-2 bg-gray-100 px-4 py-2.5 rounded-full transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Retour au compte
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-green-800 font-medium flex items-center gap-2 shadow-sm">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_380px] items-start">
        
        {{-- ZONE DE CHAT --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm flex flex-col h-[650px] overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center">
                    <img src="{{ asset('logo.jpg') }}" alt="Stepora" class="w-full h-full object-cover rounded-full p-1 bg-white">
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Support Stepora</h3>
                    <div class="flex items-center gap-1.5 text-xs text-green-600 font-medium">
                        <span class="w-2 h-2 rounded-full bg-green-500 relative flex"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                        En ligne
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50/50 scroll-smooth" id="chatContainer" style="scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
                @if($messages->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-center px-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <p class="text-lg font-bold text-gray-900 mb-1">Aucun message pour le moment.</p>
                        <p class="text-sm text-gray-500 max-w-sm">Vous avez une question sur une commande ou un article ? N'hésitez pas à nous écrire.</p>
                    </div>
                @else
                    @foreach($messages as $msg)
                        @if($msg->is_admin)
                            {{-- Bulle Admin (Gauche) --}}
                            <div class="flex justify-start w-full group">
                                <div class="max-w-[85%] sm:max-w-[75%]">
                                    <div class="flex items-center gap-2 mb-1.5 ml-1">
                                        <span class="text-xs font-bold text-gray-900">Support Stepora</span>
                                        <span class="text-[10px] font-medium text-gray-400">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm px-5 py-3.5 text-[0.95rem] leading-relaxed whitespace-pre-wrap shadow-sm">
                                        {{ $msg->message }}
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Bulle Client (Droite) --}}
                            <div class="flex justify-end w-full group">
                                <div class="max-w-[85%] sm:max-w-[75%]">
                                    <div class="flex items-center justify-end gap-2 mb-1.5 mr-1">
                                        <span class="text-[10px] font-medium text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="text-xs font-bold text-gray-900">Vous</span>
                                    </div>
                                    <div class="bg-blue-600 text-white rounded-2xl rounded-tr-sm px-5 py-3.5 text-[0.95rem] leading-relaxed whitespace-pre-wrap shadow-md">
                                        {{ $msg->message }}
                                    </div>
                                    @if($msg->status === 'lu' || $msg->status === 'répondu')
                                        <div class="flex justify-end mt-1 mr-1">
                                            <p class="text-[10px] font-medium text-gray-400 flex items-center gap-1">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                Vu par l'équipe
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            
            {{-- CHAT INPUT FOR MOBILE (hidden on lg, visible on small) --}}
            <div class="lg:hidden border-t border-gray-200 bg-white p-4">
                <form action="{{ route('messagerie.store') }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea 
                            name="message" 
                            rows="1" 
                            required
                            class="w-full bg-gray-100 border-transparent rounded-full pl-5 pr-12 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-black focus:border-black outline-none transition-all resize-none max-h-32"
                            placeholder="Écrivez votre message..."
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                        >{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="w-12 h-12 flex-shrink-0 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-transform active:scale-95">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- FORMULAIRE NOUVEAU MESSAGE DESKTOP --}}
        <div class="hidden lg:block bg-white border border-gray-200 rounded-2xl p-7 shadow-sm sticky top-24">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Envoyer un message</h3>
            <p class="text-sm text-gray-500 mb-6">Notre équipe vous répondra dans les plus brefs délais.</p>
            
            <form action="{{ route('messagerie.store') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <textarea 
                        name="message" 
                        id="messageDesktop" 
                        rows="6" 
                        required
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 text-[0.95rem] focus:bg-white focus:ring-2 focus:ring-black focus:border-black outline-none transition-all resize-none"
                        placeholder="Que souhaitez-vous nous dire ?"
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition-all hover:shadow-lg flex items-center justify-center gap-2 active:scale-[0.98]">
                    Envoyer le message
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    /* Custom scrollbar for webkit */
    #chatContainer::-webkit-scrollbar {
        width: 6px;
    }
    #chatContainer::-webkit-scrollbar-track {
        background: transparent;
    }
    #chatContainer::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
</style>

<script>
    // Scroll automatiquement en bas du chat
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatContainer');
        if(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });
</script>
@endsection
