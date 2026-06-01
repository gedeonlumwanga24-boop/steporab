<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(15);
        return view('admin.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        if ($message->status === 'non lu') {
            $message->update(['status' => 'lu']);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $message->update([
            'reply' => $request->reply,
            'status' => 'répondu',
        ]);

        \Illuminate\Support\Facades\Mail::to($message->email)->send(new \App\Mail\MessageReplyMail($message));

        return redirect()->route('admin.messages.index')->with('success', 'Réponse envoyée avec succès.');
    }
}
