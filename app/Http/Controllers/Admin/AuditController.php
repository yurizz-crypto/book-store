<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        // Fetch audits, latest first, eager load the user who made the change
        $query = Audit::with('user')->latest();

        // Basic filtering if the admin searches for an event type (e.g., 'updated')
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        $audits = $query->paginate(15)->withQueryString();

        return view('admin.audits.index', compact('audits'));
    }
}