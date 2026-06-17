<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentValidatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;

    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Paiement confirmé — Commande #' . str_pad($this->commande->id, 5, '0', STR_PAD_LEFT),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.commandes.payment_validated',
            with: [
                'commande'     => $this->commande,
                'client'       => $this->commande->user,
                'produits'     => $this->commande->produits,
                'lienCompte'   => route('compte.show'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
