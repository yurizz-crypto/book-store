<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {

            // 1. Composite Index for the Controller's most common filter & sort pattern 
            // (e.g., WHERE category_id = ? ORDER BY price)
            $table->index(
                ['category_id', 'price', 'id'], 
                'idx_books_category_price'
            );

            // 2. Composite Index for filtering by category and sorting by latest
            $table->index(
                ['category_id', 'created_at', 'id'], 
                'idx_books_category_latest'
            );

            // 3. Covering Index for stock/price calculations
            // Includes 'id' so pagination/lookups can be done index-only
            $table->index(
                ['price', 'stock_quantity', 'id'], 
                'idx_books_price_stock'
            );

            // 4. Lab 7 Full-Text Search Requirement
            // Assuming you will implement Laravel Scout with MySQL/PostgreSQL driver
            $table->fullText(
                ['title', 'description'], 
                'idx_books_fulltext'
            );
            
            // 5. High-speed Exact Lookup Index (Lab 7 Benchmark requirement)
            $table->index('isbn', 'idx_books_isbn_lookup');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('idx_books_category_price');
            $table->dropIndex('idx_books_category_latest');
            $table->dropIndex('idx_books_price_stock');
            $table->dropIndex('idx_books_fulltext');
            $table->dropIndex('idx_books_isbn_lookup');
        });
    }
};