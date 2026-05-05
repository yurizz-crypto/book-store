<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\UserActionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class NotifyExportCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
            public User $user, 
            public string $filename,
            public string $title = 'Export Completed',
            public string $message = 'Your file is ready.'
        ) {}

    public function handle(): void
    {
        $downloadUrl = asset('storage/' . $this->filename);

        // REMOVED: event(new \App\Events\ExportReady(...))

        // We now force this generic notification to only use the database channel
        // to prevent mail timeouts, just like we did for the admin listener.
        $this->user->notify(new UserActionNotification($this->title, [
            'event'   => $this->title,
            'details' => $this->message . ' (If it didn\'t download automatically, you can download it manually below.)',
            'url'     => $downloadUrl,
            'action_text' => 'Download Manually',
            'via'     => ['database'] // Force database only to bypass mail issues
        ]));
    }

    public function failed(Throwable $exception): void
    {
        \Log::error("Failed to send export notification to User ID {$this->user->id}: {$exception->getMessage()}");
    }
}