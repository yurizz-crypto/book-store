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
        // Automatically check if we specified channels, otherwise default to both
        return $this->data['via'] ?? ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $url = $this->data['url'] ?? url('/admin/dashboard');
        $actionText = $this->data['action_text'] ?? 'Download / View';

        $mail = (new MailMessage)
                    ->subject('System Notification: Action Completed')
                    ->greeting('Hello ' . ($notifiable->first_name ?? 'User') . ',')
                    ->line($this->message);

        if (!empty($this->data['details'])) {
            $mail->line($this->data['details']);
        }

        if (!empty($this->data['url'])) {
            $mail->action($actionText, $url);
        }

        return $mail->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message'     => $this->message,
            
            // Use array_key_exists to safely check for keys to prevent strict PHP warnings
            'event'       => array_key_exists('event', $this->data) ? $this->data['event'] : null,
            'details'     => array_key_exists('details', $this->data) ? $this->data['details'] : null,
            'url'         => array_key_exists('url', $this->data) ? $this->data['url'] : null,
            
            // Fallback to null instead of 'Download/View' so the UI hides the button
            'action_text' => array_key_exists('action_text', $this->data) ? $this->data['action_text'] : null,
        ];
    }
}