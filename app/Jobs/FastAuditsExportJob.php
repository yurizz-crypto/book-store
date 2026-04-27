<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;
use OpenSpout\Writer\CSV\Writer as CSVWriter;
use OpenSpout\Common\Entity\Row;

class FastAuditsExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Increased timeout for 1M+ record processing (Lab 7)
     */
    public $timeout = 900;
    
    protected $filters;
    protected $filename;
    protected $user;
    protected $format;

    public function __construct(array $filters, string $filename, $user, $format = 'csv')
    {
        $this->filters = $filters;
        $this->filename = $filename;
        $this->user = $user;
        $this->format = strtolower($format);
    }

    public function handle()
    {
        Log::info("Starting Audit export ({$this->format}): {$this->filename}");

        // 1. Initialize the correct high-speed writer
        /** @var \OpenSpout\Writer\WriterInterface $writer */
        $writer = match($this->format) {
            'xlsx' => new XLSXWriter(),
            default => new CSVWriter(),
        };

        // Ensure temp directory exists
        $tempPath = storage_path('app/temp/' . basename($this->filename));
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $writer->openToFile($tempPath);

        // 2. Add Optimized Headings
        $writer->addRow(Row::fromValues([
            'Audit ID', 'User ID', 'User Email', 'Event', 
            'Model Type', 'Model ID', 'Changes (Old | New)', 
            'IP Address', 'Device', 'Date'
        ]));

        // 3. Build the Base Query
        $query = DB::table('audits')
            ->leftJoin('users', 'audits.user_id', '=', 'users.id')
            ->select([
                'audits.id', 'audits.user_id', 'users.email', 'audits.event', 
                'audits.auditable_type', 'audits.auditable_id', 'audits.old_values', 
                'audits.new_values', 'audits.ip_address', 'audits.user_agent',
                'audits.created_at'
            ]);

        // Apply Reusable Filtering Logic
        if (!empty($this->filters['user'])) {
            $query->where(function($q) {
                $q->where('users.first_name', 'like', "%{$this->filters['user']}%")
                  ->orWhere('users.email', 'like', "%{$this->filters['user']}%");
            });
        }
        if (!empty($this->filters['event'])) $query->where('event', $this->filters['event']);
        if (!empty($this->filters['model'])) $query->where('auditable_type', 'like', "%{$this->filters['model']}%");

        /* | PERFORMANCE WIN (Lab 7):
        | We use cursor() to stream results from the database. 
        | This ensures we never load more than one row at a time into PHP memory.
        */
        foreach ($query->orderBy('audits.id', 'desc')->cursor() as $audit) {
            // Format the model name for readability
            $modelName = basename(str_replace('\\', '/', $audit->auditable_type));
            
            // Consolidate values to keep the spreadsheet readable
            $changes = "OLD: " . ($audit->old_values ?? '{}') . " | NEW: " . ($audit->new_values ?? '{}');

            $writer->addRow(Row::fromValues([
                $audit->id,
                $audit->user_id ?? 'N/A',
                $audit->email ?? 'System/Guest',
                strtoupper($audit->event),
                $modelName,
                $audit->auditable_id,
                $changes,
                $audit->ip_address,
                $audit->user_agent ?? 'Unknown Device',
                date('Y-m-d H:i:s', strtotime($audit->created_at))
            ]));
        }

        $writer->close();

        // 4. Move to Public Storage and Cleanup
        Storage::disk('public')->put($this->filename, fopen($tempPath, 'r+'));
        
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        // 5. Trigger the User Notification (Lab 8)
        dispatch(new \App\Jobs\NotifyExportCompleted($this->user, $this->filename));
        
        Log::info("Audit export completed: {$this->filename}");
    }
}