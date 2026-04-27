<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FastBooksImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
    
    public function handle()
    {
        $path = Storage::disk('local')->path($this->filePath);
        
        if (!file_exists($path)) {
            \Illuminate\Support\Facades\Log::error("Import failed: File not found at {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            fclose($handle);
            return;
        }

        $headers = array_map('strtolower', $headers);
        $map = array_flip($headers);

        // Pre-load categories (Small table, safe for memory)
        $categories = \Illuminate\Support\Facades\DB::table('categories')->pluck('id', 'name')->toArray();
        $defaultCategoryId = array_values($categories)[0] ?? 1;

        $batch = [];
        $rowCount = 0;

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $isbn = (string) ($row[$map['isbn'] ?? $map['book isbn'] ?? 0] ?? '');
                if (empty($isbn)) continue;

                $batch[] = [
                    'isbn'           => $isbn,
                    'title'          => $row[$map['title'] ?? $map['book title'] ?? 1] ?? 'Untitled',
                    'author'         => $row[$map['author'] ?? 2] ?? 'Unknown',
                    'price'          => (float) ($row[$map['price (php)'] ?? $map['price'] ?? 5] ?? 0),
                    'stock_quantity' => (int) ($row[$map['stock quantity'] ?? $map['stock'] ?? 6] ?? 0),
                    'category_id'    => $categories[$row[$map['category'] ?? 4]] ?? $defaultCategoryId,
                    'description'    => $row[$map['description'] ?? 7] ?? 'Imported via Bulk Upload',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];

                // Process in chunks of 1000
                if (count($batch) >= 1000) {
                    $this->processChunk($batch);
                    $rowCount += count($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $this->processChunk($batch);
                $rowCount += count($batch);
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Import error: " . $e->getMessage());
            throw $e; 
        } finally {
            fclose($handle);
            \Illuminate\Support\Facades\Storage::disk('local')->delete($this->filePath);
        }
        
        \Illuminate\Support\Facades\Cache::forget('featured_homepage_books');
        \Illuminate\Support\Facades\Cache::forget('homepage_categories');
        
        \Illuminate\Support\Facades\Log::info("Successfully processed {$rowCount} rows.");
    }

    /**
     * Optimized batch processor for high-scale tables
     */
    private function processChunk(array $batch)
    {
        // 1. Get all ISBNs in the current 1,000-row batch
        $batchIsbns = array_column($batch, 'isbn');

        // 2. Query only the ISBNs in this batch to see which already exist
        $existing = \Illuminate\Support\Facades\DB::table('books')
            ->whereIn('isbn', $batchIsbns)
            ->pluck('isbn')
            ->toArray();
            
        $existingMap = array_flip($existing);

        // 3. Filter the batch to only include books that are truly new
        $newRecords = array_filter($batch, function($item) use ($existingMap) {
            return !isset($existingMap[$item['isbn']]);
        });

        // 4. Insert only the new records
        if (!empty($newRecords)) {
            \Illuminate\Support\Facades\DB::table('books')->insert($newRecords);
        }
    }
}