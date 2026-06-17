<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageModel;
    public $replyText;

    /**
     * @param Message $messageModel  Le message original du client
     * @param string  $replyText     Le texte de la réponse de l'admin
     */
    public function __construct(Message $messageModel, string $replyText)
    {
        $this->messageModel = $messageModel;
        $this->replyText    = $replyText;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réponse à votre message — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.messages.reply',
            with: [
                'nom'             => $this->messageModel->nom,
                'messageOriginal' => $this->messageModel->message,
                'reponse'         => $this->replyText,
                'lienMessagerie'  => route('messagerie.index'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
