<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected bool $isEnabled) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $status = $this->isEnabled ? 'ENABLED' : 'DISABLED';

        return (new MailMessage)
            ->subject('PageTurner Security Alert: 2FA Status Changed')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('This is a security notification regarding your account.')
            ->line('Two-Factor Authentication (2FA) has been successfully **' . $status . '**.')
            ->line('If you did not authorize this change, please secure your account and reset your password immediately.')
            ->action('View Security Settings', route('profile.edit'))
            ->line('Thank you for helping us keep PageTurner secure!');
    }

    public function toArray($notifiable): array
    {
        $status = $this->isEnabled ? 'ENABLED' : 'DISABLED';
        return [
            'title' => 'Security Alert: 2FA',
            'details' => [
                'event' => '2FA ' . $status,
                'target' => 'Your Account Settings',
                'url' => route('profile.edit', [], false)
            ]
        ];
    }
}