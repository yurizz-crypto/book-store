<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProcessAndNotifyBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        // 1. Run the backup synchronously in the background worker
        Artisan::call('backup:run', ['--only-db' => true]);

        $backupName = config('backup.backup.name');
        $disk = Storage::disk('local');
        
        $files = $disk->allFiles($backupName);
        
        if (!empty($files)) {
            usort($files, function($a, $b) use ($disk) {
                return $disk->lastModified($b) <=> $disk->lastModified($a);
            });

            $latestBackup = $files[0];
            
            // 2. Prepare the public download path
            $publicFilename = 'exports/backup_' . now()->timestamp . '.zip';
            
            // 3. Move the file from private to public storage
            Storage::disk('public')->put($publicFilename, $disk->get($latestBackup));

            /* | FIX: Pass specific backup labels to the notification job.
            | This prevents the notification from saying "Export Completed".
            */
            dispatch(new \App\Jobs\NotifyExportCompleted(
                $this->user, 
                $publicFilename,
                'System Backup Ready', // Title
                'The database backup has been generated successfully and is ready for download.' // Message
            ));
        }
    }
}