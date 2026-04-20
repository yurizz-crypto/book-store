<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Models\User;
use App\Notifications\NewOrderReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

// Optional: Add "implements ShouldQueue" to the class if you want the 
// listener itself to run in the background, though your notification is already queued!
class SendNewOrderNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        // 1. Get all admin users who should receive this alert
        $admins = User::where('role', 'admin')->get();

        // 2. Send the notification, passing the order from the event
        Notification::send($admins, new NewOrderReceived($event->order));
    }
}