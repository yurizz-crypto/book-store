<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add a specialized search vector column
        DB::statement("ALTER TABLE books ADD COLUMN IF NOT EXISTS search_vector tsvector;");

        // 2. Index that column using a GIN index (The fastest search index in Postgres)
        DB::statement("CREATE INDEX IF NOT EXISTS idx_books_search_vector ON books USING GIN(search_vector);");

        // 3. Populate the vector with existing data from title and author
        DB::statement("UPDATE books SET search_vector = to_tsvector('english', coalesce(title, '') || ' ' || coalesce(author, ''));");

        // 4. Create a Trigger to keep the search vector updated automatically when books change
        DB::statement("
            CREATE TRIGGER trg_books_search_update BEFORE INSERT OR UPDATE
            ON books FOR EACH ROW EXECUTE FUNCTION
            tsvector_update_trigger(search_vector, 'pg_catalog.english', title, author);
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS trg_books_search_update ON books;");
        DB::statement("DROP INDEX IF EXISTS idx_books_search_vector;");
        DB::statement("ALTER TABLE books DROP COLUMN IF EXISTS search_vector;");
    }
};