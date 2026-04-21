<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldQueue, WithCustomChunkSize
{
    use Exportable;

    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Order::query()->with('user');

        // Filter by Status
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        // Filter by Date Range
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        // Filter by Customer
        if (!empty($this->filters['user_id'])) {
            $query->where('user_id', $this->filters['user_id']);
        }

        return $query;
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function headings(): array
    {
        return ['Order ID', 'Customer Name', 'Customer Email', 'Total Amount', 'Status', 'Order Date'];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->user->first_name . ' ' . $order->user->last_name,
            $order->user->email,
            $order->total_amount,
            strtoupper($order->status),
            $order->created_at->format('Y-m-d H:i'),
        ];
    }
}