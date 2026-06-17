<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $sujet;
    public string $type;
    public string $typeLabel;
    public string $message;
    public string $clientNom;
    public string $lienBoutique;

    public function __construct(
        string $sujet,
        string $type,
        string $typeLabel,
        string $message,
        string $clientNom,
        string $lienBoutique,
    ) {
        $this->sujet        = $sujet;
        $this->type         = $type;
        $this->typeLabel    = $typeLabel;
        $this->message      = $message;
        $this->clientNom    = $clientNom;
        $this->lienBoutique = $lienBoutique;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->typeLabel . ' — ' . $this->sujet,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.newsletter.broadcast',
            with: [
                'sujet'        => $this->sujet,
                'type'         => $this->type,
                'typeLabel'    => $this->typeLabel,
                'corps'        => $this->message,
                'clientNom'    => $this->clientNom,
                'lienBoutique' => $this->lienBoutique,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
