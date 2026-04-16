<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OwenIt\Auditing\Audit;
use Illuminate\Support\Facades\Storage;

class ArchiveAudits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:archive';

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
        $oldAudits = Audit::where('created_at', '<', now()->subMonths(3))->get();
        
        if ($oldAudits->count() > 0) {
            $filename = 'audits_archive_' . now()->format('Y_m') . '.json';
            Storage::disk('local')->put('archives/' . $filename, $oldAudits->toJson());
            
            // Delete from database after archiving
            Audit::where('created_at', '<', now()->subMonths(3))->delete();
        }
        
        $this->info('Old audits safely archived.');
    }
}
