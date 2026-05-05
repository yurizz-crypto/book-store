<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FastFinancialExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $dateFrom;
    protected $dateTo;
    protected $filename;
    protected $user;

    public function __construct($dateFrom, $dateTo, $filename, $user)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->filename = $filename;
        $this->user = $user;
    }

    public function handle()
    {
        $stats = DB::table('orders')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo])
            ->selectRaw('COUNT(*) as total_orders, SUM(total_amount) as gross_revenue')
            ->first();

        $gross = $stats->gross_revenue ?? 0;
        $tax = $gross * 0.12;
        $net = $gross - $tax;

        $tempPath = tempnam(sys_get_temp_dir(), 'fin_');
        $handle = fopen($tempPath, 'w');
        
        fputcsv($handle, ['Reporting Period', 'Total Completed Orders', 'Gross Revenue (PHP)', 'Estimated Tax (PHP)', 'Net Revenue (PHP)']);
        fputcsv($handle, [
            $this->dateFrom . ' to ' . $this->dateTo,
            $stats->total_orders,
            number_format($gross, 2),
            number_format($tax, 2),
            number_format($net, 2)
        ]);

        fclose($handle);
        
        // Save File
        Storage::disk('public')->put($this->filename, fopen($tempPath, 'r+'));
        unlink($tempPath);

        // 1. Fire WebSockets Event Instantly
        $downloadUrl = asset('storage/' . $this->filename);
        event(new \App\Events\ExportReady($this->user->id, $downloadUrl));

        // 2. Dispatch Background Notification
        dispatch(new \App\Jobs\NotifyExportCompleted(
            $this->user, 
            $this->filename,
            'Financial Export Ready',
            'Your requested financial report has been generated.'
        ));
    }
}