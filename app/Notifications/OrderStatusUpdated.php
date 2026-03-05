<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Eager load for the string ID and table relationships
        $this->order->load(['orderItems.book', 'user.addresses']);

        return (new MailMessage)
            ->subject('Order Update: #' . $this->order->id)
            ->markdown('emails.orders.status', [
                'order' => $this->order,
                'user'  => $notifiable,
            ]);
    }
}