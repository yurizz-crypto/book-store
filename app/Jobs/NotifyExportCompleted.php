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

        // 1. Dispatch the WebSocket event to trigger the auto-download instantly
        event(new \App\Events\ExportReady($this->user->id, $downloadUrl));

        // 2. Send the informational notification as a fallback/record
        $this->user->notify(new UserActionNotification($this->title, [
            'event'   => $this->title,
            'details' => $this->message . ' (This file should have downloaded automatically. If it didn\'t, you can download it manually below.)',
            'url'     => $downloadUrl,
            'action_text' => 'Download Manually' // Change button text
        ]));
    }

    public function failed(Throwable $exception): void
    {
        \Log::error("Failed to send export notification to User ID {$this->user->id}: {$exception->getMessage()}");
    }
}