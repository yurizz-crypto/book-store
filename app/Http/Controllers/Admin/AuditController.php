<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

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
        // Detect format from request, default to csv
        $format = $request->input('format', 'csv');
        $filename = 'exports/audits_' . now()->timestamp . '.' . $format;

        // Pass the format to the Job
        \App\Jobs\FastAuditsExportJob::dispatch(
            $request->all(), 
            $filename, 
            $request->user(), 
            $format
        );

        return back()->with('success', "Audit export ({$format}) queued!");
    }
}