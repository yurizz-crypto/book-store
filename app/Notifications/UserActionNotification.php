<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class UserActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $action;
    protected $data;

    public function __construct(string $action, array $data = [])
    {
        $this->action = $action;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        if ($notifiable instanceof User) {
            if ($notifiable->role === 'admin') {
                return [
                    'title' => 'System Alert: ' . $this->action, 
                    'details' => $this->data,
                    'type' => 'admin_alert',
                ];
            } elseif ($notifiable->role === 'customer') {
                return [
                    'title' => 'Notification: ' . $this->action, 
                    'details' => $this->data,
                    'type' => 'customer_notification',
                ];
            }
        }

        return [
            'title' => 'General Notification: ' . $this->action,
            'details' => $this->data,
            'type' => 'general',
        ];
    }

    public function toMail($notifiable)
    {
        $dbData = $this->toDatabase($notifiable);
        
        return (new MailMessage)
            ->subject($dbData['message'])
            ->greeting('Hello ' . ($notifiable->name ?? 'User') . '!')
            ->line($dbData['message'])
            ->line('Details: ' . json_encode($dbData['details']))
            ->action('View in Dashboard', url('/dashboard'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}