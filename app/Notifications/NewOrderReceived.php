<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $this->order->load(['orderItems.book', 'user']);

        return (new MailMessage)
            ->subject('New Order Received: #' . $this->order->id)
            ->markdown('emails.orders.processed', [
                'order' => $this->order,
                'customerName' => $this->order->user->first_name . ' ' . $this->order->user->last_name,
                'total' => number_format($this->order->total_amount, 2),
                'url' => route('admin.orders.index'),
            ]);
    }
}