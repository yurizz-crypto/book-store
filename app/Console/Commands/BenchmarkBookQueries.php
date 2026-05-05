<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BenchmarkBookQueries extends Command
{
    protected $signature = 'benchmark:books {--iterations=100 : Number of iterations to run}';
    protected $description = 'Run performance benchmarks on critical book queries';

    public function handle()
    {
        $iterations = (int) $this->option('iterations');
        $this->info("Starting Benchmark with {$iterations} iterations...");

        // Warmup Pass
        $this->info("Warming up database and cache...");
        Book::first();
        
        $results = [
            'Catalog Listing' => ['target' => 100, 'time' => $this->benchmarkCatalog($iterations)],
            'Search by ISBN' => ['target' => 50, 'time' => $this->benchmarkIsbn($iterations)],
            'Category Filter' => ['target' => 150, 'time' => $this->benchmarkCategory($iterations)],
            'Full-Text Search' => ['target' => 300, 'time' => $this->benchmarkScout($iterations)],
        ];

        $failed = false;

        $this->table(
            ['Query Type', 'Target (ms)', 'Actual Avg (ms)', 'Status'],
            collect($results)->map(function ($data, $name) use (&$failed) {
                $status = $data['time'] <= $data['target'] ? '<fg=green>PASS</>' : '<fg=red>FAIL</>';
                if ($data['time'] > $data['target']) $failed = true;
                
                return [$name, $data['target'], round($data['time'], 2), $status];
            })->toArray()
        );

        if ($failed) {
            $this->error('One or more queries failed to meet the performance targets.');
            return Command::FAILURE; // Non-zero exit code for CI/CD
        }

        $this->info('All performance benchmarks passed successfully!');
        return Command::SUCCESS;
    }

    private function benchmarkCatalog(int $iterations): float
    {
        return $this->measure(function () {
            Book::select(['id', 'isbn', 'title', 'author', 'price', 'stock_quantity', 'published_at', 'category_id'])
                ->with(['category:id,name']) // <--- Removed 'slug' here
                ->where('is_active', true)
                ->orderBy('published_at', 'desc')
                ->orderBy('id', 'desc')
                ->cursorPaginate(100);
        }, $iterations);
    }

    private function benchmarkIsbn(int $iterations): float
    {
        $isbn = Book::inRandomOrder()->value('isbn') ?? '978-0-000000-00-0';
        return $this->measure(function () use ($isbn) {
            Book::where('isbn', $isbn)->first();
        }, $iterations);
    }

    private function benchmarkCategory(int $iterations): float
    {
        $categoryId = Book::inRandomOrder()->value('category_id') ?? 1;
        return $this->measure(function () use ($categoryId) {
            Book::where('category_id', $categoryId)
                ->where('is_active', true)
                ->limit(100)
                ->get();
        }, $iterations);
    }

    private function benchmarkScout(int $iterations): float
    {
        return $this->measure(function () {
            Book::search('Harry Potter')->get();
        }, $iterations);
    }

    private function measure(callable $callback, int $iterations): float
    {
        $start = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $callback();
        }
        $end = microtime(true);
        
        return (($end - $start) / $iterations) * 1000; // Return average in milliseconds
    }
}