<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class FinancialExport implements FromCollection, WithHeadings, WithTitle
{
    protected $dateFrom;
    protected $dateTo;

    public function __construct($dateFrom = null, $dateTo = null)
    {
        $this->dateFrom = $dateFrom ?? now()->startOfMonth();
        $this->dateTo = $dateTo ?? now()->endOfMonth();
    }

    public function collection()
    {
        $query = Order::where('status', 'completed')
            ->whereBetween('created_at', [$this->dateFrom, $this->dateTo]);

        $totalOrders = $query->count();
        $totalRevenue = (float) $query->sum('total_amount'); 
        
        $estimatedTax = $totalRevenue * 0.12;
        $netRevenue = $totalRevenue - $estimatedTax;

        return collect([[
            'Period' => $this->dateFrom->format('Y-m-d') . ' to ' . $this->dateTo->format('Y-m-d'),
            'Total Orders' => $totalOrders,
            'Gross Revenue' => number_format($totalRevenue, 2),
            'Estimated Tax (12%)' => number_format($estimatedTax, 2),
            'Net Revenue' => number_format($netRevenue, 2),
        ]]);
    }

    public function headings(): array
    {
        return ['Reporting Period', 'Total Completed Orders', 'Gross Revenue (PHP)', 'Estimated Tax (PHP)', 'Net Revenue (PHP)'];
    }

    public function title(): string
    {
        return 'Financial Summary';
    }
}