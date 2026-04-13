<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $action;
    protected $data;

    /**
     * Create a new notification instance.
     *
     * @param string $action A string describing the action (e.g., 'order_placed', 'product_reviewed')
     * @param array $data Additional data relevant to the notification
     * @return void
     */
    public function __construct(string $action, array $data = [])
    {
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // For in-system notifications, we'll use the 'database' channel.
        // You could add other channels like 'mail', 'broadcast' here if needed.
        return ['mail', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // This method is typically used for broadcast or mail notifications.
        // For database notifications, `toDatabase` is more relevant.
        return [
            'action' => $this->action,
            'data' => $this->data,
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        // Here's where you implement the role-based logic.
        // The $notifiable will be the User model instance that is receiving the notification.
        if ($notifiable instanceof User) {
            if ($notifiable->role === 'admin') {
                // Logic for admin notifications
                return [
                    'message' => 'Admin Alert: ' . $this->action . ' occurred.',
                    'details' => $this->data,
                    'type' => 'admin_alert',
                ];
            } elseif ($notifiable->role === 'customer') {
                // Logic for customer notifications
                return [
                    'message' => 'Notification: ' . $this->action . '.',
                    'details' => $this->data,
                    'type' => 'customer_notification',
                ];
            }
        }

        // Default notification if role is not recognized or not a User instance
        return [
            'message' => 'General Notification: ' . $this->action . '.',
            'details' => $this->data,
            'type' => 'general',
        ];
    }
}
