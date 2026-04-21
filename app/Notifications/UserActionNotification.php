<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $data; // Property to hold the extra data

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Default to dashboard if no URL is provided
        $url = $this->data['url'] ?? url('/admin/dashboard');

        $mail = (new MailMessage)
                    ->subject('System Notification: Action Completed')
                    ->greeting('Hello ' . ($notifiable->first_name ?? 'User') . ',')
                    ->line($this->message);

        // Add the details line if it exists
        if (!empty($this->data['details'])) {
            $mail->line($this->data['details']);
        }

        return $mail->action('Download / View', $url)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification for the database.
     */
    public function toArray($notifiable)
    {
        // This makes sure the URL actually saves to the database
        return [
            'message' => $this->message,
            'event'   => $this->data['event'] ?? null,
            'details' => $this->data['details'] ?? null,
            'url'     => $this->data['url'] ?? null,
        ];
    }
}