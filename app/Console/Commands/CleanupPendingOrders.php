<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'order:cleanup-pending';

    /**
     * The console command description.
     */
    protected $description = 'Cancel pending orders older than 24 hours and restore book stock';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Find pending orders older than 24 hours
        $staleOrders = Order::where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->with('orderItems.book')
            ->get();

        if ($staleOrders->isEmpty()) {
            $this->info('No stale pending orders found.');
            return;
        }

        $count = 0;

        foreach ($staleOrders as $order) {
            DB::transaction(function () use ($order) {
                foreach ($order->orderItems as $item) {
                    if ($item->book) {
                        $item->book->increment('stock_quantity', $item->quantity);
                    }
                }
                
                $order->update(['status' => 'cancelled']);
            });
            
            $count++;
        }

        $message = "Auto-cancelled {$count} pending order(s) and restored stock.";
        $this->info($message);
        Log::channel('daily')->info("ORDER CLEANUP: " . $message);
    }
}