<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class GenerateDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate-daily';

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
        $todaySales = Order::whereDate('created_at', now()->today())
                        ->where('status', 'completed')
                        ->sum('total_amount');
                        
        Log::channel('daily')->info("DAILY REPORT: Total revenue today was PHP " . number_format($todaySales, 2));
        
        $this->info('Daily report generated.');
    }
}
