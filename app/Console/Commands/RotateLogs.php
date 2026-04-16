<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RotateLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:rotate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $archiveName = storage_path('logs/laravel-' . now()->format('Y-m-d') . '.log');
            rename($logPath, $archiveName);
            file_put_contents($logPath, ''); // Create a fresh empty log
        }
        $this->info('Server logs rotated successfully.');
    }
}
