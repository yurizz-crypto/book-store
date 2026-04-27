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
    public $data; 

    public function __construct($message, $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = $this->data['url'] ?? url('/admin/dashboard');
        $actionText = $this->data['action_text'] ?? 'Download / View'; // Dynamic text

        $mail = (new MailMessage)
                    ->subject('System Notification: Action Completed')
                    ->greeting('Hello ' . ($notifiable->first_name ?? 'User') . ',')
                    ->line($this->message);

        if (!empty($this->data['details'])) {
            $mail->line($this->data['details']);
        }

        // Only show button if URL exists
        if (!empty($this->data['url'])) {
            $mail->action($actionText, $url);
        }

        return $mail->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message'     => $this->message,
            'event'       => $this->data['event'] ?? null,
            'details'     => $this->data['details'] ?? null,
            'url'         => $this->data['url'] ?? null,
            'action_text' => $this->data['action_text'] ?? 'Download / View',
        ];
    }
}