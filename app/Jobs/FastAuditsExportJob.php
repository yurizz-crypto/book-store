<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FastAuditsExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $this->format = $format;
    }

    public function handle()
    {
        if ($this->format === 'csv') {
            $this->handleCsvExport();
        } else {
            $this->handleExcelExport();
        }

        // Notify user via existing job
        dispatch(new \App\Jobs\NotifyExportCompleted($this->user, $this->filename));
    }

    private function handleCsvExport()
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'audits_');
        $handle = fopen($tempPath, 'w');
        
        // CSV Headings - Added 'Device'
        fputcsv($handle, ['Audit ID', 'User ID', 'User Email', 'Event', 'Model Type', 'Model ID', 'Old Values', 'New Values', 'IP Address', 'Device', 'Date']);

        $query = $this->getBaseQuery();

        // High-speed cursor streaming
        foreach ($query->cursor() as $audit) {
            fputcsv($handle, [
                $audit->id,
                $audit->user_id ?? 'N/A',
                $audit->email ?? 'System/Guest',
                strtoupper($audit->event),
                basename(str_replace('\\', '/', $audit->auditable_type)),
                $audit->auditable_id,
                $audit->old_values, 
                $audit->new_values,
                $audit->ip_address,
                $audit->user_agent ?? 'Unknown Device', // Added Device
                date('Y-m-d H:i:s', strtotime($audit->created_at))
            ]);
        }

        fclose($handle);
        Storage::disk('public')->put($this->filename, fopen($tempPath, 'r+'));
        unlink($tempPath);
    }

    private function handleExcelExport()
    {
        $excelFormat = match($this->format) {
            'pdf' => \Maatwebsite\Excel\Excel::DOMPDF,
            'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
            default => \Maatwebsite\Excel\Excel::CSV,
        };

        // Uses a dynamic export class to handle XLSX/PDF via the library
        Excel::store(new class($this->getBaseQuery()) implements \Maatwebsite\Excel\Concerns\FromQuery, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping {
            protected $query;
            public function __construct($query) { $this->query = $query; }
            public function query() { return $this->query; }
            public function headings(): array {
                // Added 'Device' to headings
                return ['Audit ID', 'User ID', 'User Email', 'Event', 'Model Type', 'Model ID', 'Old Values', 'New Values', 'IP Address', 'Device', 'Date'];
            }
            public function map($audit): array {
                return [
                    $audit->id,
                    $audit->user_id ?? 'N/A',
                    $audit->email ?? 'System/Guest',
                    strtoupper($audit->event),
                    basename(str_replace('\\', '/', $audit->auditable_type)),
                    $audit->auditable_id,
                    $audit->old_values, 
                    $audit->new_values,
                    $audit->ip_address,
                    $audit->user_agent ?? 'Unknown Device', // Added Device
                    date('Y-m-d H:i:s', strtotime($audit->created_at))
                ];
            }
        }, $this->filename, 'public', $excelFormat);
    }

    private function getBaseQuery()
    {
        $query = DB::table('audits')
            ->leftJoin('users', 'audits.user_id', '=', 'users.id')
            ->select([
                'audits.id', 'audits.user_id', 'users.email', 'audits.event', 
                'audits.auditable_type', 'audits.auditable_id', 'audits.old_values', 
                'audits.new_values', 'audits.ip_address', 'audits.user_agent',
                'audits.created_at'
            ])
            ->orderBy('audits.id', 'desc'); // <-- ADD THIS LINE

        // Reusable filtering logic
        if (!empty($this->filters['user'])) {
            $query->where(function($q) {
                $q->where('users.first_name', 'like', "%{$this->filters['user']}%")
                  ->orWhere('users.email', 'like', "%{$this->filters['user']}%");
            });
        }
        if (!empty($this->filters['event'])) $query->where('event', $this->filters['event']);
        if (!empty($this->filters['model'])) $query->where('auditable_type', 'like', "%{$this->filters['model']}%");

        return $query;
    }
}