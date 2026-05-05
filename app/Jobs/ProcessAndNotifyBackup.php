<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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
        Artisan::call('backup:run', ['--only-db' => true]);

        $backupName = config('backup.backup.name');
        $disk = Storage::disk('local');
        
        $files = $disk->allFiles($backupName);
        
        if (!empty($files)) {
            usort($files, function($a, $b) use ($disk) {
                return $disk->lastModified($b) <=> $disk->lastModified($a);
            });

            $latestBackup = $files[0];
            
            $publicFilename = 'exports/backup_' . now()->timestamp . '.zip';
            Storage::disk('public')->put($publicFilename, $disk->get($latestBackup));
            
            $downloadUrl = asset('storage/' . $publicFilename);

            // 1. FIRE THE WEBSOCKET EVENT IMMEDIATELY
            // Do not dispatch a job, broadcast the event right now so the 
            // user's browser instantly starts the download.
            event(new \App\Events\ExportReady($this->user->id, $downloadUrl));

            // 2. DISPATCH THE INFORMATIONAL NOTIFICATION JOB
            // This can happen safely in the background while the user is already downloading
            dispatch(new \App\Jobs\NotifyExportCompleted(
                $this->user, 
                $publicFilename,
                'System Backup Ready',
                'The database backup has been generated successfully. It should have downloaded automatically.'
            ));
        }
    }
}