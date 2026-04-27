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
use Throwable;

class NotifyExportCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
            public User $user, 
            public string $filename,
            public string $title = 'Export Completed', // Added default
            public string $message = 'Your file is ready for download.' // Added default
        ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Use asset() to ensure the URL is absolute and bypasses 404 redirect issues
        $downloadUrl = asset('storage/' . $this->filename);

        $this->user->notify(new UserActionNotification($this->title, [
            'event'   => $this->title,
            'details' => $this->message,
            'url'     => $downloadUrl
        ]));
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        // Optional: Log the failure or notify an admin if the notification couldn't be sent
        \Log::error("Failed to send export notification to User ID {$this->user->id}: {$exception->getMessage()}");
    }
}