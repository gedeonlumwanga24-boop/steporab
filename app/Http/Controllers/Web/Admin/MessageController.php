<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // On récupère la dernière interaction par email
        $messages = Message::selectRaw('MAX(id) as max_id')
            ->groupBy('email')
            ->pluck('max_id');

        $conversations = Message::whereIn('id', $messages)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.messages.index', compact('conversations'));
    }

    public function show(Message $message)
    {
        $email = $message->email;

        // Marquer les messages de cet utilisateur comme lus (sauf les nôtres)
        Message::where('email', $email)
            ->where('is_admin', false)
            ->where('status', 'non lu')
            ->update(['status' => 'lu']);

        $thread = Message::where('email', $email)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.messages.show', compact('thread', 'email', 'message'));
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $replyText = $request->reply;

        // Créer un nouveau message côté admin
        Message::create([
            'is_admin'   => true,
            'nom'        => 'Support Stepora',
            'email'      => $message->email,
            'message'    => $replyText,
            'status'     => 'répondu',
        ]);

        // Mettre à jour le statut du dernier message client
        Message::where('email', $message->email)
            ->where('is_admin', false)
            ->latest('id')
            ->first()
            ?->update(['status' => 'répondu']);

        // Envoyer l'email de notification avec le lien vers la messagerie
        \Illuminate\Support\Facades\Mail::to($message->email)
            ->send(new \App\Mail\MessageReplyMail($message, $replyText));

        return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
    }
}
