<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FastOrdersExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    protected $filters;
    protected $filename;
    protected $user;

    public function __construct(array $filters, string $filename, $user)
    {
        $this->filters = $filters;
        $this->filename = $filename;
        $this->user = $user;
    }

    public function handle()
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'orders_');
        $handle = fopen($tempPath, 'w');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['Order ID', 'Customer Name', 'Customer Email', 'Total Amount', 'Status', 'Order Date']);

        $query = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select([
                'orders.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as customer_name"),
                'users.email',
                'orders.total_amount',
                'orders.status',
                'orders.created_at'
            ]);

        if (!empty($this->filters['status'])) $query->where('orders.status', $this->filters['status']);
        if (!empty($this->filters['user_id'])) $query->where('orders.user_id', $this->filters['user_id']);
        if (!empty($this->filters['date_from'])) $query->whereDate('orders.created_at', '>=', $this->filters['date_from']);
        if (!empty($this->filters['date_to'])) $query->whereDate('orders.created_at', '<=', $this->filters['date_to']);

        foreach ($query->orderBy('orders.id')->cursor() as $order) {
            fputcsv($handle, [
                $order->id,
                $order->customer_name,
                $order->email,
                $order->total_amount,
                strtoupper($order->status),
                date('Y-m-d H:i', strtotime($order->created_at))
            ]);
        }

        fclose($handle);
        
        // Save file
        Storage::disk('public')->put($this->filename, fopen($tempPath, 'r+'));
        unlink($tempPath);

        // 1. Fire WebSockets Event Instantly
        $downloadUrl = asset('storage/' . $this->filename);
        event(new \App\Events\ExportReady($this->user->id, $downloadUrl));

        // 2. Dispatch Background Notification
        dispatch(new \App\Jobs\NotifyExportCompleted(
            $this->user, 
            $this->filename,
            'Orders Export Ready',
            'Your orders data export has been generated.'
        ));
    }
}