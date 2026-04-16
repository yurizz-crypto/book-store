<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:cleanup';

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
        // If using database sessions, delete sessions inactive for > 24 hours
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('last_activity', '<', now()->subDays(1)->getTimestamp())
            ->delete();
            
        $this->info('Old sessions cleaned up successfully.');
    }
}
