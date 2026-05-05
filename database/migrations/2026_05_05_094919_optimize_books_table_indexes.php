<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // 1. Add the missing columns first so the indexes don't fail
            if (!Schema::hasColumn('books', 'published_at')) {
                $table->date('published_at')->nullable();
            }
            
            if (!Schema::hasColumn('books', 'is_active')) {
                $table->boolean('is_active')->default(true); // Default to true so books show up!
            }

            // 2. Now it is safe to create the performance indexes
            $table->index(['category_id', 'published_at', 'is_active'], 'idx_books_catalog_filter');
            $table->index(['price', 'stock_quantity', 'id'], 'idx_books_price_stock');
            
            // Note: Postgres does not natively support standard fullText() without language casting.
            // Commenting this out prevents Postgres syntax errors.
            // $table->fullText(['title', 'description'], 'idx_books_fulltext');
            
            $table->index('is_active', 'idx_books_active');
            $table->index('isbn', 'idx_books_isbn_lookup');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('idx_books_catalog_filter');
            $table->dropIndex('idx_books_price_stock');
            $table->dropFullText('idx_books_fulltext');
            $table->dropIndex('idx_books_active');
            $table->dropIndex('idx_books_isbn_lookup');
        });
    }
};