<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Contracts\Queue\ShouldQueue;

class BooksExport implements FromQuery, WithHeadings, WithMapping, WithCustomChunkSize, ShouldQueue, WithEvents
{
    use Exportable;

    protected $filters;
    protected $columns;

    public function __construct(array $filters = [], array|string $columns = [])
    {
        $this->filters = $filters;
        $this->columns = empty($columns) 
            ? ['id', 'isbn', 'title', 'author', 'category', 'price', 'stock', 'date'] 
            : (array) $columns; 
    }

    public function query()
    {
        $query = Book::query()
            ->select([
                'books.id',
                'books.isbn',
                'books.title',
                'books.author',
                'books.price',
                'books.stock_quantity',
                'books.created_at',
                'categories.name as category_name'
            ])
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id');   // ← Join instead of with()

        // Keep your existing filters
        if (!empty($this->filters['category_id'])) {
            $query->where('books.category_id', $this->filters['category_id']);
        }
        if (isset($this->filters['stock_status'])) {
            if ($this->filters['stock_status'] === 'in_stock') $query->where('books.stock_quantity', '>', 0);
            elseif ($this->filters['stock_status'] === 'out_of_stock') $query->where('books.stock_quantity', '<=', 0);
        }
        if (!empty($this->filters['price_min'])) $query->where('books.price', '>=', $this->filters['price_min']);
        if (!empty($this->filters['price_max'])) $query->where('books.price', '<=', $this->filters['price_max']);
        if (!empty($this->filters['date_from'])) $query->whereDate('books.created_at', '>=', $this->filters['date_from']);
        if (!empty($this->filters['date_to'])) $query->whereDate('books.created_at', '<=', $this->filters['date_to']);

        return $query;
    }

    public function chunkSize(): int
    {
        return 1500;   // ← Sweet spot: not too small, not too big
    }

    public function headings(): array
    {
        $map = [
            'id'       => 'Book ID',
            'isbn'     => 'ISBN',
            'title'    => 'Title',
            'author'   => 'Author',
            'category' => 'Category',
            'price'    => 'Price (PHP)',
            'stock'    => 'Stock Quantity',
            'date'     => 'Added On'
        ];

        return array_map(fn($col) => $map[$col] ?? $col, $this->columns);
    }

    public function map($book): array
    {
        $row = [];
        foreach ($this->columns as $col) {
            $row[] = match($col) {
                'id'       => $book->id,
                'isbn'     => $book->isbn,
                'title'    => $book->title,
                'author'   => $book->author,
                'category' => $book->category_name ?? 'Uncategorized',   // ← Use joined column
                'price'    => $book->price,
                'stock'    => $book->stock_quantity,
                'date'     => $book->created_at?->format('Y-m-d') ?? 'N/A',
                default    => 'N/A',
            };
        }
        return $row;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                gc_collect_cycles();
                if (function_exists('memory_get_usage')) {
                    // Optional: log memory usage for monitoring
                }
            },
        ];
    }
}