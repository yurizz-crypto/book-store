<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\UserActionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NotifyExportCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user, 
        public string $filename
    ) {}

    public function handle(): void
    {
        // Generate a secure download link from your storage disk
        $downloadUrl = Storage::disk('public')->url($this->filename);

        // Utilize your existing notification class
        $this->user->notify(new UserActionNotification('Export Completed', [
            'event'   => 'Your Orders Export is Ready',
            'details' => 'The background export process has finished successfully. Click to download your file.',
            'url'     => $downloadUrl
        ]));
    }
}