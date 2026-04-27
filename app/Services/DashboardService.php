<?php

namespace App\Services;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Ai\Agents\ReviewAnalystAgent;

class DashboardService
{
    /**
     * Cache duration in seconds (e.g., 900 = 15 minutes)
     */
    private const CACHE_TTL = 900;

    /**
     * Get metrics for the standard user dashboard.
     */
    public function getUserMetrics(User $user): array
    {
        return [
            'totalOrders'  => $user->orders()->where('status', '!=', 'cart')->count(),
            'totalSpent'   => $user->orders()->where('status', 'completed')->sum('total_amount'),
            'recentOrders' => $user->orders()->where('status', '!=', 'cart')->latest()->take(5)->get(),
            
            // Lab 7 Optimization: Select only necessary columns
            'recentBooks'  => Book::select(['id', 'title', 'cover_image'])
                ->whereHas('orderItems.order', function($q) use ($user) {
                    $q->where('user_id', $user->id)->where('status', 'completed');
                })->latest()->take(4)->get(),
            
            // Lab 7 Optimization: Eager Load with column limits
            'recentReviews' => $user->reviews()
                ->with('book:id,title') 
                ->latest()
                ->take(3)
                ->get(),
        ];
    }

    /**
     * Get metrics for the admin dashboard, utilizing caching for heavy queries.
     */
    public function getAdminMetrics(): array
    {
        return [
            'stats'         => Cache::remember('admin_stats', self::CACHE_TTL, fn() => $this->getGeneralStats()),
            'velocity'      => Cache::remember('admin_velocity', self::CACHE_TTL, fn() => $this->getSalesVelocity()),
            'userGrowth'    => Cache::remember('admin_user_growth', self::CACHE_TTL, fn() => $this->getUserGrowth()),
            'topBooks'      => Cache::remember('admin_top_books', self::CACHE_TTL, fn() => $this->getTopBooks()),
            'topCategories' => Cache::remember('admin_top_categories', self::CACHE_TTL, fn() => $this->getTopCategories()),
            'statusSummary' => Cache::remember('admin_status_summary', self::CACHE_TTL, fn() => $this->getStatusSummary()),
            
            'lowStock'      => $this->getLowStockBooks(),
            
            // Lab 7: Optimized Eager Loading for Admin
            'recentOrders'  => Order::with('user:id,first_name,last_name,email')->latest()->take(6)->get(),
            'recentReviews' => $this->getAnalyzedReviews(),
        ];
    }

    private function getAnalyzedReviews()
    {
        return Cache::remember('admin_ai_reviews', self::CACHE_TTL, function () {
            // Lab 7: Eager load relationships with column limits
            $reviews = Review::with(['user:id,first_name', 'book:id,title'])->latest()->take(4)->get();
            $aiManager = app(AiServiceManager::class);

            return $reviews->map(function ($review) use ($aiManager) {
                try {
                    // Uses the fallback chain (OpenAI -> Gemini -> Ollama)
                    $review->ai_analysis = $aiManager->generateWithFallback(
                        "Summarize this customer review in 10 words: " . $review->comment,
                        'admin_dashboard_summary'
                    );
                } catch (\Exception $e) {
                    $review->ai_analysis = "Analysis unavailable.";
                }
                return $review;
            });
        });
    }

    private function getSalesVelocity(): array 
    {
        $today = Order::whereDate('created_at', today())->where('status', 'completed')->sum('total_amount');
        $yesterday = Order::whereDate('created_at', today()->subDay())->where('status', 'completed')->sum('total_amount');
        
        $growth = $yesterday > 0 
            ? (($today - $yesterday) / $yesterday) * 100 
            : ($today > 0 ? 100 : 0);

        return [
            'today' => $today,
            'growth_percentage' => round($growth, 1),
            'trend' => $growth >= 0 ? 'up' : 'down'
        ];
    }

    private function getGeneralStats(): array 
    {
        return [
            'users'      => User::count(),
            'books'      => Book::count(),
            'categories' => Category::count(),
            'orders'     => Order::count(),
            'revenue'    => Order::where('status', 'completed')->sum('total_amount'),
            'avgOrder'   => Order::where('status', 'completed')->avg('total_amount') ?? 0,
        ];
    }

    private function getUserGrowth() 
    {
        return User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();
    }

    private function getTopBooks() 
    {
        return Book::join('order_items', 'books.id', '=', 'order_items.book_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('books.id', 'books.title', DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_earned'))
            ->groupBy('books.id', 'books.title')
            ->orderByDesc('total_earned')
            ->take(3)
            ->get();
    }

    private function getTopCategories() 
    {
        return Category::join('books', 'categories.id', '=', 'books.category_id')
            ->join('order_items', 'books.id', '=', 'order_items.book_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('categories.id', 'categories.name', DB::raw('COUNT(order_items.id) as sales_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('sales_count')
            ->take(3)
            ->get();
    }

    private function getStatusSummary()
    {
        return Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
    }

    private function getLowStockBooks() 
    {
        return Book::select(['id', 'title', 'stock_quantity', 'price'])
            ->where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(5, ['*'], 'stock_page');
    }
}