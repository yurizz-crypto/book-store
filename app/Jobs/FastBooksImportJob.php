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
            Log::error("Import failed: File not found at {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        
        // 1. DYNAMIC HEADER DETECTION
        // This maps 'ISBN' in the CSV to the 'isbn' column, regardless of its position
        $headers = fgetcsv($handle);
        $headers = array_map('strtolower', $headers);
        $map = array_flip($headers);

        // Pre-load categories
        $categories = DB::table('categories')->pluck('id', 'name')->toArray();
        $defaultCategoryId = array_values($categories)[0] ?? 1;

        $batch = [];
        $rowCount = 0;

        try {
            while (($row = fgetcsv($handle)) !== false) {
                // 2. SAFE MAPPING
                // We use the detected map or fall back to null/0 to prevent crashes
                $batch[] = [
                    'isbn'           => (string) ($row[$map['isbn'] ?? $map['book isbn'] ?? 0] ?? ''),
                    'title'          => $row[$map['title'] ?? $map['book title'] ?? 1] ?? 'Untitled',
                    'author'         => $row[$map['author'] ?? 2] ?? 'Unknown',
                    'price'          => (float) ($row[$map['price (php)'] ?? $map['price'] ?? 5] ?? 0),
                    'stock_quantity' => (int) ($row[$map['stock quantity'] ?? $map['stock'] ?? 6] ?? 0),
                    'category_id'    => $categories[$row[$map['category'] ?? 4]] ?? $defaultCategoryId,
                    'description'    => $row[$map['description'] ?? 7] ?? 'Imported via Bulk Upload',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];

                if (count($batch) >= 1000) {
                    $this->upsertBatch($batch);
                    $rowCount += count($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) {
                $this->upsertBatch($batch);
                $rowCount += count($batch);
            }

        } catch (\Exception $e) {
            Log::error("Import error at row {$rowCount}: " . $e->getMessage());
            throw $e; // Re-throw to mark job as failed in Laravel logs
        } finally {
            fclose($handle);
            Storage::disk('local')->delete($this->filePath);
        }
        
        Cache::forget('featured_homepage_books');
        Cache::forget('homepage_categories');
        
        Log::info("Successfully imported {$rowCount} books.");
    }

    private function upsertBatch(array $batch)
    {
        DB::table('books')->upsert(
            $batch, 
            ['isbn'], // Unique constraint
            ['title', 'author', 'price', 'stock_quantity', 'category_id', 'description', 'updated_at'] // Columns to update
        );
    }
}