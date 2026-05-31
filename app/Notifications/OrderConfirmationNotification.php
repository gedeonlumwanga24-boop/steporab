<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Commande $order) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de commande #' . $this->order->id)
            ->greeting('Bonjour ' . ($notifiable->display_name ?? ''))
            ->line('Votre commande a bien été enregistrée.')
            ->line('Total : ' . number_format($this->order->total, 0, ',', ' ') . ' FCFA')
            ->action('Voir ma commande', url('/compte'))
            ->line('Merci pour votre confiance !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'total' => $this->order->total,
            'message' => 'Commande #' . $this->order->id . ' confirmée.',
        ];
    }
}
