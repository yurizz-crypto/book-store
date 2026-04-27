<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshBestsellerStats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:refresh-bestseller-stats';

    /**
     * The console command description.
     */
    protected $description = 'Refreshes the materialized view for bestseller statistics (Lab 7)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Refreshing materialized view...');
        
        // CONCURRENTLY allows the view to be read while it's refreshing (Requires the Unique Index we added)
        DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY mv_bestseller_stats');
        
        $this->info('Materialized view refreshed successfully!');
    }
}