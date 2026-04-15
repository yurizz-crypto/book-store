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
        
        $filename = 'pageturner_books_' . now()->format('Y-m-d_H-i');
        
        if ($format === 'csv') {
            return Excel::download(new BooksExport($filters, $columns), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        } elseif ($format === 'pdf') {
            return Excel::download(new BooksExport($filters, $columns), $filename . '.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
        
        return Excel::download(new BooksExport($filters, $columns), $filename . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function importBooks(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv|max:10240',
        ]);

        Excel::import(new BooksImport, $request->file('import_file'));

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
}