<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The precise index needed to make Cursor Pagination instant in Postgres
        DB::statement('CREATE INDEX IF NOT EXISTS idx_cursor_pagination ON books (is_active, published_at DESC, id DESC);');

        // 2. Enable Postgres Trigram matching to speed up Laravel Scout's ILIKE queries
        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');
        
        // 3. Add Trigram indexes to the most heavily searched columns
        DB::statement('CREATE INDEX IF NOT EXISTS idx_books_title_trgm ON books USING GIN (title gin_trgm_ops);');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_books_author_trgm ON books USING GIN (author gin_trgm_ops);');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS idx_cursor_pagination;');
        DB::statement('DROP INDEX IF EXISTS idx_books_title_trgm;');
        DB::statement('DROP INDEX IF EXISTS idx_books_author_trgm;');
    }
};