<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataPortabilityController extends Controller
{
    /**
     * Admin Book Export - Optimized for Speed
     */
    public function exportBooks(Request $request)
    {
        $filters = $request->only(['category_id', 'stock_status', 'price_min', 'price_max', 'date_from', 'date_to']);
        $format = $request->input('format', 'csv'); 
        $filename = 'exports/books_' . now()->timestamp . '.' . $format;

        \App\Jobs\FastBooksExportJob::dispatch($filters, $filename, $request->user(), $format);

        return back()->with('success', "Book export ({$format}) started!");
    }

    /**
     * Bulk Book Import via Queue
     */
    public function importBooks(Request $request)
    {
        $request->validate(['import_file' => 'required|mimes:csv,xlsx|max:204800']);
        
        // Store file in private storage for the Job to pick up
        $path = $request->file('import_file')->store('temp-imports');

        // Dispatch the background job
        \App\Jobs\FastBooksImportJob::dispatch($path);

        // NEW: If the request comes from JavaScript, return a JSON success message
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'File uploaded! Book import queued for high-speed processing.'
            ]);
        }

        // Fallback for standard HTML form submissions
        return back()->with('success', 'Book import queued for high-speed processing!');
    }
    
    public function importUsers(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt,xlsx',
            // Make sure it validates the role too
            'default_role' => 'required|string|in:customer,admin' 
        ]);

        $file = $request->file('import_file');
        $filePath = $file->store('imports', 'local');
        
        // 1. Grab the role from the form
        $role = $request->input('default_role'); 

        // 2. Pass BOTH the file path AND the role to the Job
        \App\Jobs\FastUsersImportJob::dispatch($filePath, $role);

        return back()->with('success', 'User import started!');
    }

    /**
     * CSV Template Generator using Streams for minimal memory usage
     */
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

    /**
     * Manual Backup Trigger - Process and Notify
     */
    public function triggerBackup(Request $request)
    {
        $adminUser = $request->user();

        \App\Jobs\ProcessAndNotifyBackup::dispatch($adminUser);
        
        return back()->with('success', 'Backup has been started! You will receive a notification with the download link once it is finished.');
    }

    /**
     * Admin Order Export
     */
    public function exportOrders(Request $request) 
    {
        $format = $request->input('format', 'csv');
        $filename = 'exports/orders_' . now()->timestamp . '.' . $format;
        
        \App\Jobs\FastOrdersExportJob::dispatch($request->all(), $filename, $request->user(), $format);
        return back()->with('success', "Orders export ({$format}) queued!");
    }
    /**
     * User Export with Optional PII Redaction
     */
    public function exportUsers(Request $request) {
        $format = $request->input('format', 'csv');
        $filename = 'exports/users_' . now()->timestamp . '.' . $format;
        
        \App\Jobs\FastUsersExportJob::dispatch($request->has('redact_pii'), $filename, $request->user(), $format);
        return back()->with('success', "Users export ({$format}) queued!");
    }
}