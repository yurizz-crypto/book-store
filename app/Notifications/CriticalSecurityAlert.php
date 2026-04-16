<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CriticalSecurityAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $eventDetails;

    public function __construct(array $eventDetails)
    {
        $this->eventDetails = $eventDetails;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->error()
                    ->subject('CRITICAL ALERT: Security Event Detected')
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('A critical security event has been detected on the PageTurner platform:')
                    ->line('**Event:** ' . $this->eventDetails['event'])
                    ->line('**Target Email:** ' . $this->eventDetails['target'])
                    ->line('**IP Address:** ' . $this->eventDetails['ip'])
                    ->action('Investigate Audit Logs', route('admin.audits.index'))
                    ->line('Please review the system audit logs immediately to ensure system integrity.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Critical Security Event',
            'details' => array_merge($this->eventDetails, ['url' => route('admin.audits.index', [], false)])
        ];
    }
}