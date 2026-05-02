<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    /**
     * Créer un nouveau message de contact
     */
    public function createMessage(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * Récupérer tous les messages
     */
    public function getAllMessages(): Collection
    {
        return Message::all();
    }

    /**
     * Récupérer les messages non lus
     */
    public function getUnreadMessages(): Collection
    {
        return Message::where('read', false)->get();
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(int $id): bool
    {
        $message = Message::find($id);
        if (!$message) return false;
        return $message->update(['read' => true]);
    }

    /**
     * Supprimer un message
     */
    public function deleteMessage(int $id): bool
    {
        $message = Message::find($id);
        if (!$message) return false;
        return $message->delete();
    }
}
