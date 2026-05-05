<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\UserActionNotification;
use Illuminate\Support\Facades\Notification;
use Spatie\Backup\Events\BackupWasSuccessful;

class NotifyAdminsOfBackupStatus
{
    public function handle(object $event): void
    {
        $isSuccess = $event instanceof BackupWasSuccessful;
        $title = $isSuccess ? 'System Backup Successful' : 'System Backup FAILED';
        $details = $isSuccess 
            ? 'Disaster recovery archive has been generated and stored locally.' 
            : 'The automated system backup failed to complete.';

        $admins = User::where('role', 'admin')->get();

        Notification::send($admins, new UserActionNotification('System Backup', [
            'event'       => $title,
            'details'     => $details,
            
            // Explicitly set these to null to prevent missing key errors
            // and tell the frontend NOT to render a button
            'url'         => null, 
            'action_text' => null,
            
            'via'         => ['database'] 
        ]));
    }
}