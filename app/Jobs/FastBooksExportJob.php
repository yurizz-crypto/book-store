<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;
use OpenSpout\Writer\CSV\Writer as CSVWriter;
use OpenSpout\Writer\WriterInterface;
use OpenSpout\Common\Entity\Row;

class FastBooksExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    protected $filters;
    protected $filename;
    protected $user;
    protected $format;

    public function __construct(array $filters, string $filename, $user, $format = 'csv')
    {
        $this->filters = $filters;
        $this->filename = $filename;
        $this->user = $user;
        $this->format = strtolower($format);
    }

    public function handle()
    {
        Log::info("Starting Books export ({$this->format}): {$this->filename}");

        /** @var WriterInterface $writer */
        $writer = match($this->format) {
            'xlsx' => new XLSXWriter(),
            default => new CSVWriter(),
        };

        $tempPath = storage_path('app/temp/' . basename($this->filename));
        if (!file_exists(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        $writer->openToFile($tempPath);

        // Header Row
        $writer->addRow(Row::fromValues([
            'Book ID', 'ISBN', 'Title', 'Author', 'Category', 
            'Price (PHP)', 'Stock Quantity', 'Added On'
        ]));

        $query = DB::table('books')
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->select([
                'books.id', 'books.isbn', 'books.title', 'books.author',
                'books.price', 'books.stock_quantity',
                'categories.name as category_name', 'books.created_at'
            ]);

        // Filters
        if (!empty($this->filters['category_id'])) {
            $query->where('books.category_id', $this->filters['category_id']);
        }
        
        if (isset($this->filters['stock_status'])) {
            if ($this->filters['stock_status'] === 'in_stock') {
                $query->where('books.stock_quantity', '>', 0);
            } elseif ($this->filters['stock_status'] === 'out_of_stock') {
                $query->where('books.stock_quantity', '<=', 0);
            }
        }

        // Optimized Streaming
        foreach ($query->orderBy('books.id')->cursor() as $book) {
            $writer->addRow(Row::fromValues([
                $book->id,
                $book->isbn,
                $book->title,
                $book->author,
                $book->category_name ?? 'Uncategorized',
                $book->price,
                $book->stock_quantity,
                date('Y-m-d', strtotime($book->created_at)),
            ]));
        }

        $writer->close();

        Storage::disk('public')->put($this->filename, fopen($tempPath, 'r+'));
        
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        dispatch(new \App\Jobs\NotifyExportCompleted($this->user, $this->filename));
    }
}