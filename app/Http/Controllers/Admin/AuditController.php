<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

use App\Exports\AuditsExport;
use Maatwebsite\Excel\Facades\Excel;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user')->latest();

        if ($request->filled('user')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->user . '%')
                ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('model')) {
            $query->where('auditable_type', 'like', '%' . $request->model . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->paginate(20)->withQueryString();

        return view('admin.audits.index', compact('audits'));
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        $format = $request->input('format', 'csv');
        $filename = 'system_audits_' . now()->format('Ymd_His');

        if ($format === 'pdf') {
            return Excel::download(new AuditsExport($filters), $filename . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
        
        return Excel::download(new AuditsExport($filters), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}