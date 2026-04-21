<?php

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Models\User;
use App\Notifications\NewReviewAlert;
use Illuminate\Support\Facades\Notification;

class SendNewReviewNotification
{
    /**
     * Handle the event.
     */
    public function handle(ReviewSubmitted $event): void
    {
        // 1. Fetch all system administrators
        $admins = User::where('role', 'admin')->get();

        // 2. Send the alert, passing the review from the event
        Notification::send($admins, new NewReviewAlert($event->review));
    }
}