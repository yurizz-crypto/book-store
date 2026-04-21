<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\UserActionNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Backup\Events\BackupWasSuccessful;

class NotifyAdminsOfBackupStatus
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $isSuccess = $event instanceof BackupWasSuccessful;
        $title = $isSuccess ? 'System Backup Successful' : 'System Backup FAILED';

        $admins = User::where('role', 'admin')->get();

        // PERFORMANCE FIX: Use Notification::send() instead of a manual foreach loop.
        // This is vastly more efficient and integrates perfectly with Laravel's queue system.
        Notification::send($admins, new UserActionNotification('System Backup', [
            'event'   => $title,
            'details' => 'Disaster recovery archive has been generated and stored locally.',
            'url'     => '/admin/dashboard' 
        ]));
    }
}