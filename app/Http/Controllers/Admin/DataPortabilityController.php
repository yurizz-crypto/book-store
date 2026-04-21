<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\BooksExport;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataPortabilityController extends Controller
{
    public function exportBooks(Request $request)
    {
        $filters = $request->only(['category_id', 'stock_status', 'price_min', 'price_max', 'date_from', 'date_to']);
        $columns = $request->input('columns', []);
        $format = $request->input('format', 'xlsx');
        
        $filename = 'exports/pageturner_books_' . now()->format('Y-m-d_H-i-s') . '.' . $format;
        
        $adminUser = $request->user();

        (new BooksExport($filters, $columns))
            ->queue($filename, 'public')
            ->chain([
                new \App\Jobs\NotifyExportCompleted($adminUser, $filename)
            ]);

        return back()->with('success', 'Books export queued! You will receive a notification with the download link when the file is ready.');
    }

    public function importBooks(Request $request)
    {

        \Log::info('Import request received', [
        'has_file' => $request->hasFile('import_file'),
        'file_valid' => $request->file('import_file')?->isValid(),
        'error' => $request->file('import_file')?->getError(),
        ]);

        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv|max:20480',
        ]);

        Excel::queueImport(new BooksImport, $request->file('import_file'));

        return back()->with('success', 'Book import has been queued! It will process in the background.');
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = ['ISBN', 'Title', 'Author', 'Price', 'Stock', 'Category', 'Description'];
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, ['9781234567890', 'Sample Book', 'John Doe', '299.99', '50', 'Fiction', 'A sample description']);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=pageturner_import_template.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ]);
    }

    public function exportOrders(Request $request)
    {
        $filters = $request->only(['status', 'date_from', 'date_to', 'user_id']);
        $format = $request->input('format', 'xlsx');
        
        // Save the file in an exports folder inside your storage/app/public directory
        $filename = 'exports/pageturner_orders_' . now()->format('Y-m-d_His') . '.' . $format;

        $adminUser = $request->user();

        // 1. Queue the export
        // 2. Chain the notification job to fire immediately after it finishes
        (new \App\Exports\OrdersExport($filters))
            ->queue($filename, 'public')
            ->chain([
                new \App\Jobs\NotifyExportCompleted($adminUser, $filename)
            ]);

        return back()->with('success', 'Orders export queued! You will receive a notification with the download link when the file is ready.');
    }

    // Financial Report Export
    public function exportFinancials(Request $request)
    {
        $dateFrom = $request->input('date_from') ? \Carbon\Carbon::parse($request->input('date_from')) : null;
        $dateTo = $request->input('date_to') ? \Carbon\Carbon::parse($request->input('date_to')) : null;
        
        return Excel::download(new \App\Exports\FinancialExport($dateFrom, $dateTo), 'financial_report_' . now()->format('F_Y') . '.xlsx');
    }

    // Manual Backup Trigger
    public function triggerBackup()
    {
        // Use queue() instead of call() so the user isn't stuck waiting
        \Illuminate\Support\Facades\Artisan::queue('backup:run');
        
        return back()->with('success', 'Backup has been started in the background! You will receive an email when it completes.');
    }

    // Export Users (with GDPR Check)
    public function exportUsers(Request $request)
    {
        // Check if the admin checked the "Redact PII" box
        $redactPII = $request->has('redact_pii'); 
        $filename = 'pageturner_users_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new \App\Exports\UsersExport($redactPII), $filename);
    }

    // Import Corporate Users
    public function importUsers(Request $request)
    {
        $request->validate([
            'users_file' => 'required|mimes:xlsx,csv|max:10240',
            'default_role' => 'required|in:admin,customer'
        ]);

        Excel::queueImport(new \App\Imports\UsersImport($request->default_role), $request->file('users_file'));

        return back()->with('success', 'Corporate users queued for import!');
    }
}