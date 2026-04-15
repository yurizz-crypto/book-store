<?php

namespace App\Exports;

use App\Models\Book;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

// Added ShouldQueue to fulfill the "> 10,000 records" requirement
class BooksExport implements FromQuery, WithHeadings, WithMapping, ShouldQueue
{
    use Exportable;

    protected $filters;
    protected $columns;

    // Accept filters and specific columns chosen by the user
    public function __construct(array $filters = [], array $columns = [])
    {
        $this->filters = $filters;
        // If no columns selected, default to all
        $this->columns = empty($columns) ? ['id', 'isbn', 'title', 'author', 'category', 'price', 'stock', 'date'] : $columns;
    }

    public function query()
    {
        $query = Book::query()->with('category');

        // Category Filter
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        // Stock Filter
        if (isset($this->filters['stock_status'])) {
            if ($this->filters['stock_status'] === 'in_stock') $query->where('stock_quantity', '>', 0);
            elseif ($this->filters['stock_status'] === 'out_of_stock') $query->where('stock_quantity', '<=', 0);
        }

        // Price Range Filter (NEW)
        if (!empty($this->filters['price_min'])) $query->where('price', '>=', $this->filters['price_min']);
        if (!empty($this->filters['price_max'])) $query->where('price', '<=', $this->filters['price_max']);

        // Date Range Filter (NEW)
        if (!empty($this->filters['date_from'])) $query->whereDate('created_at', '>=', $this->filters['date_from']);
        if (!empty($this->filters['date_to'])) $query->whereDate('created_at', '<=', $this->filters['date_to']);

        return $query;
    }

    public function headings(): array
    {
        // Only return headings for the columns the user selected
        $headings = [];
        $map = [
            'id' => 'Book ID', 'isbn' => 'ISBN', 'title' => 'Title', 
            'author' => 'Author', 'category' => 'Category', 
            'price' => 'Price (PHP)', 'stock' => 'Stock Quantity', 'date' => 'Added On'
        ];

        foreach ($this->columns as $col) {
            if (isset($map[$col])) $headings[] = $map[$col];
        }
        return $headings;
    }

    public function map($book): array
    {
        // Only map data for the columns the user selected
        $row = [];
        foreach ($this->columns as $col) {
            match($col) {
                'id' => $row[] = $book->id,
                'isbn' => $row[] = $book->isbn,
                'title' => $row[] = $book->title,
                'author' => $row[] = $book->author,
                'category' => $row[] = $book->category ? $book->category->name : 'Uncategorized',
                'price' => $row[] = $book->price,
                'stock' => $row[] = $book->stock_quantity,
                'date' => $row[] = $book->created_at->format('Y-m-d'),
                default => $row[] = ''
            };
        }
        return $row;
    }
}