<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Safety Check: Only query the DB if the table AND column actually exist
        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'last_activity')) {
            
            DB::table('sessions')
                ->where('last_activity', '<', now()->subDays(1)->getTimestamp())
                ->delete();
                
            $this->info('Old database sessions cleaned up successfully.');
            
        } else {
            // Safely skip without crashing if using file sessions
            $this->info('System is using file sessions. Database cleanup skipped.');
        }
    }
}
