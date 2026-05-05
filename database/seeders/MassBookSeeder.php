<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MassBookSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Prevent memory limits and timeouts
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '0'); 
        
        DB::disableQueryLog();
        DB::statement('TRUNCATE TABLE books RESTART IDENTITY CASCADE');

        $categoryIds = \App\Models\Category::pluck('id')->toArray();
        
        if (empty($categoryIds)) {
            $this->command->error('No categories found. Please seed categories first.');
            return;
        }

        $totalRecords = 1000000; // Scaled to 1 Million
        $chunkSize = 5000; 

        $this->command->getOutput()->progressStart($totalRecords);

        // 2. Cache the timestamp string once outside the loop
        // Calling now() 1 million times creates 1 million Carbon instances, which eats memory and time.
        $timestamp = now()->toDateTimeString();

        for ($i = 0; $i < ($totalRecords / $chunkSize); $i++) {
            $books = [];
            
            for ($j = 0; $j < $chunkSize; $j++) {
                // 3. Create a sequential index for fast, unique string generation
                $currentIndex = ($i * $chunkSize) + $j; 
                
                $books[] = [
                    // str_pad is drastically faster than generating random strings
                    'isbn' => '978' . str_pad($currentIndex, 10, '0', STR_PAD_LEFT), 
                    'title' => 'Book Title ' . $currentIndex, 
                    'author' => 'Author ' . mt_rand(1, 1000), // Simulating 1000 unique authors is realistic and fast
                    'price' => mt_rand(10000, 200000) / 100, 
                    'stock_quantity' => mt_rand(0, 500),
                    'category_id' => $categoryIds[array_rand($categoryIds)],
                    'description' => 'Standard description for book number ' . $currentIndex . '. ' . md5($currentIndex), 
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
            
            // Insert the chunk
            DB::table('books')->insert($books);
            
            // Advance the progress bar
            $this->command->getOutput()->progressAdvance($chunkSize);
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info("\nSuccessfully seeded 1 million books!");
    }
}