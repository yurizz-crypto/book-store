<?php

namespace App\Imports;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithUpserts;

class BooksImport implements 
    ToModel, 
    WithHeadingRow, 
    WithValidation, 
    WithBatchInserts, 
    WithChunkReading, 
    ShouldQueue, 
    SkipsOnFailure,
    WithUpserts
{
    use SkipsFailures;

    private $categories;
    private $importedCount = 0;

    public function __construct()
    {
        $this->categories = Category::pluck('id', 'name')->toArray();
        if (empty($this->categories)) {
            throw new \Exception('No categories found in database');
        }
    }

    public function model(array $row)
    {
        $this->importedCount++;
        
        $categoryId = $this->categories[$row['category']] ?? Category::first()->id;

        return new Book([
            'isbn'           => (string) $row['isbn'],
            'title'          => $row['title'],
            'author'         => $row['author'],
            'price'          => $row['price_php'] ?? $row['price'] ?? 0, 
            'stock_quantity' => $row['stock_quantity'] ?? $row['stock'] ?? 0,
            'category_id'    => $categoryId,
            'description'    => $row['description'] ?? 'Imported via Bulk Upload',
        ]);
    }

    public function __destruct()
    {
        if ($this->importedCount > 0) {
            Cache::forget('featured_homepage_books');
            Cache::forget('homepage_categories');
        }
    }

    public function uniqueBy()
    {
        return 'isbn';
    }

    public function rules(): array
    {
        // Removed the strict '|string' validation so numeric Excel data passes!
        return [
            'isbn'           => 'required', 
            'title'          => 'required|max:255',
            'author'         => 'required|max:255',
            'category'       => 'required',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}