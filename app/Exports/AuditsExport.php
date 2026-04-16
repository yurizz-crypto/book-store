<?php

namespace App\Exports;

use OwenIt\Auditing\Models\Audit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AuditsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Audit::query()->with('user')->latest();

        if (!empty($this->filters['user'])) {
            $query->whereHas('user', function($q) {
                $q->where('first_name', 'like', '%' . $this->filters['user'] . '%')
                  ->orWhere('email', 'like', '%' . $this->filters['user'] . '%');
            });
        }
        if (!empty($this->filters['event'])) {
            $query->where('event', $this->filters['event']);
        }
        if (!empty($this->filters['model'])) {
            $query->where('auditable_type', 'like', '%' . $this->filters['model'] . '%');
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query;
    }

    public function headings(): array
    {
        return ['Audit ID', 'User', 'Event', 'Model Type', 'Model ID', 'Old Values', 'New Values', 'IP Address', 'Date'];
    }

    public function map($audit): array
    {
        return [
            $audit->id,
            $audit->user ? $audit->user->email : 'System/Guest',
            strtoupper($audit->event),
            class_basename($audit->auditable_type),
            $audit->auditable_id,
            json_encode($audit->old_values),
            json_encode($audit->new_values),
            $audit->ip_address,
            $audit->created_at->format('Y-m-d H:i:s'),
        ];
    }
}