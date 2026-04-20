<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LargeScaleBookSeeder extends Seeder
{
public function run(): void
    {
        ini_set('memory_limit', '-1');
        DB::disableQueryLog();
        DB::statement('TRUNCATE TABLE books RESTART IDENTITY CASCADE');

        $categoryIds = \App\Models\Category::pluck('id')->toArray();

        $totalRecords = 50000;
        $chunkSize = 1000; 
        
        $this->command->info('Inserting 50,000 diverse books...');
        $this->command->getOutput()->progressStart($totalRecords);

        for ($i = 0; $i < ($totalRecords / $chunkSize); $i++) {
            $books = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $books[] = [
                    'isbn' => fake()->unique()->isbn13(),
                    'title' => fake()->sentence(3),
                    'author' => fake()->name(),
                    'price' => fake()->randomFloat(2, 100, 2000),
                    'stock_quantity' => fake()->numberBetween(0, 500),
                    'category_id' => fake()->randomElement($categoryIds), 
                    'description' => fake()->paragraph(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            \App\Models\Book::insert($books);
            $this->command->getOutput()->progressAdvance($chunkSize);
            unset($books);
        }

        $this->command->getOutput()->progressFinish();
        $this->command->info('Successfully loaded a very diverse bookstore!');
    }
}