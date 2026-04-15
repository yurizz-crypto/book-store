<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BooksExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Book::query()->with('category');

        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        if (isset($this->filters['stock_status'])) {
            if ($this->filters['stock_status'] === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($this->filters['stock_status'] === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Book ID',
            'ISBN',
            'Title',
            'Author',
            'Category',
            'Price (PHP)',
            'Stock Quantity',
            'Added On'
        ];
    }

    public function map($book): array
    {
        return [
            $book->id,
            $book->isbn,
            $book->title,
            $book->author,
            $book->category ? $book->category->name : 'Uncategorized',
            $book->price,
            $book->stock_quantity,
            $book->created_at->format('Y-m-d'),
        ];
    }
}