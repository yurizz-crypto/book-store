<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\BooksExport;
use App\Imports\BooksImport; // Add this
use Maatwebsite\Excel\Facades\Excel;

class DataPortabilityController extends Controller
{
    public function exportBooks(Request $request)
    {
        $filters = $request->only(['category_id', 'stock_status']);
        $filename = 'pageturner_books_' . now()->format('Y-m-d_H-i') . '.xlsx';
        return Excel::download(new BooksExport($filters), $filename);
    }

    public function importBooks(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv|max:10240',
        ]);

        Excel::import(new BooksImport, $request->file('import_file'));

        return back()->with('success', 'Book import has been queued! It will process in the background.');
    }
}