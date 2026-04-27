<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Lab 7: Materialized View for Admin Reporting
        // Note: We use created_at since your seeder didn't use published_at
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

        // Optional: Create an index on the view for even faster lookups
        DB::statement("CREATE UNIQUE INDEX idx_mv_bestseller_category ON mv_bestseller_stats(category_id);");
    }

    public function down(): void
    {
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mv_bestseller_stats;");
    }
};