<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop database-level Foreign Keys pointing to the books table.
        // Eloquent will continue to manage relationships perfectly via PHP.
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
        });

        // 2. Temporarily drop the Materialized View
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_bestseller_stats;');

        // 3. Rename existing table
        DB::statement('ALTER TABLE books RENAME TO books_unpartitioned;');

        // 4. Create the new partitioned table
        DB::statement('
            CREATE TABLE books (
                LIKE books_unpartitioned INCLUDING DEFAULTS
            ) PARTITION BY RANGE (created_at);
        ');

        // 5. Postgres Rule: Partition key must be in the Primary Key
        DB::statement('ALTER TABLE books ADD PRIMARY KEY (id, created_at);');

        // 6. Transfer the ID auto-increment sequence ownership to the new partitioned table
        DB::statement('ALTER SEQUENCE books_id_seq OWNED BY books.id;');

        // 7. Create Partitions
        DB::statement("CREATE TABLE books_p2024 PARTITION OF books FOR VALUES FROM ('2024-01-01') TO ('2025-01-01');");
        DB::statement("CREATE TABLE books_p2025 PARTITION OF books FOR VALUES FROM ('2025-01-01') TO ('2026-01-01');");
        DB::statement("CREATE TABLE books_p2026 PARTITION OF books FOR VALUES FROM ('2026-01-01') TO ('2027-01-01');");
        DB::statement("CREATE TABLE books_p_future PARTITION OF books FOR VALUES FROM ('2027-01-01') TO (MAXVALUE);");

        // 8. Move the 1,000,000 records
        DB::statement('INSERT INTO books SELECT * FROM books_unpartitioned;');

        // 9. DROP the old table (CASCADE safely removes any remaining internal dependencies)
        DB::statement('DROP TABLE books_unpartitioned CASCADE;');

        // 10. Now safely recreate the Lab 7 Optimized Indexes
        DB::statement('CREATE INDEX idx_books_category_price ON books(category_id, price, id);');
        DB::statement('CREATE INDEX idx_books_category_latest ON books(category_id, created_at, id);');
        DB::statement('CREATE INDEX idx_books_price_stock ON books(price, stock_quantity, id);');
        DB::statement('CREATE INDEX idx_books_isbn_lookup ON books(isbn);');

        // 11. Recreate the Materialized View so it points to the new partitioned table
        DB::statement("
            CREATE MATERIALIZED VIEW mv_bestseller_stats AS
            SELECT 
                category_id,
                COUNT(*) as total_books,
                ROUND(AVG(price), 2) as avg_price,
                SUM(stock_quantity) as total_inventory,
                COUNT(CASE WHEN stock_quantity > 500 THEN 1 END) as bestseller_count,
                MAX(created_at) as latest_publication
            FROM books
            GROUP BY category_id;
        ");
        DB::statement("CREATE UNIQUE INDEX idx_mv_bestseller_category ON mv_bestseller_stats(category_id);");
    }

    public function down(): void
    {
        // Revert process
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_bestseller_stats;');
        DB::statement('ALTER TABLE books RENAME TO books_partitioned;');
        DB::statement('CREATE TABLE books (LIKE books_partitioned INCLUDING DEFAULTS);');
        DB::statement('ALTER TABLE books ADD PRIMARY KEY (id);');
        DB::statement('ALTER SEQUENCE books_id_seq OWNED BY books.id;');
        DB::statement('INSERT INTO books SELECT * FROM books_partitioned;');
        DB::statement('DROP TABLE books_partitioned CASCADE;'); 
        
        DB::statement('CREATE INDEX idx_books_category_price ON books(category_id, price, id);');
        DB::statement('CREATE INDEX idx_books_category_latest ON books(category_id, created_at, id);');
        DB::statement('CREATE INDEX idx_books_price_stock ON books(price, stock_quantity, id);');
        DB::statement('CREATE INDEX idx_books_isbn_lookup ON books(isbn);');

        // Put foreign keys back
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }
};