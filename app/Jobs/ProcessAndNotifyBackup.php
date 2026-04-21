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
        // 1. Run the backup command synchronously within this background worker
        Artisan::call('backup:run', ['--only-db' => true]); // Added --only-db for speed if preferred

        // 2. Identify the backup directory (defined in your backup.php config)
        $backupName = config('backup.backup.name'); //
        $disk = Storage::disk('local'); //
        
        // 3. Find the latest backup file in the local storage
        $files = $disk->allFiles($backupName);
        
        if (!empty($files)) {
            // Sort files by modification time to get the newest
            usort($files, function($a, $b) use ($disk) {
                return $disk->lastModified($b) <=> $disk->lastModified($a);
            });

            $latestBackup = $files[0];
            
            /**
             * 4. IMPORTANT: Backups are stored in 'local' (private). 
             * To make it downloadable via your existing NotifyExportCompleted job,
             * we copy it to the 'public' disk.
             */
            $publicFilename = 'exports/backup_' . now()->timestamp . '.zip';
            Storage::disk('public')->put($publicFilename, $disk->get($latestBackup));

            // 5. Trigger your existing notification job with the link
            dispatch(new \App\Jobs\NotifyExportCompleted($this->user, $publicFilename));
        }
    }
}