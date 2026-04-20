<?php

namespace App\Services;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getAdminMetrics(): array
    {
        return [
            'stats'         => $this->getGeneralStats(),
            'velocity'      => $this->getSalesVelocity(), // NEW
            'userGrowth'    => $this->getUserGrowth(),
            'topBooks'      => $this->getTopBooks(),
            'topCategories' => $this->getTopCategories(),
            'lowStock'      => $this->getLowStockBooks(),
            'recentOrders'  => Order::with('user')->latest()->take(6)->get(),
            'statusSummary' => Order::select('status', DB::raw('count(*) as total'))->groupBy('status')->get(),
            'recentReviews' => Review::with(['user', 'book'])->latest()->take(4)->get(),
        ];
    }

    private function getSalesVelocity(): array 
    {
        $today = Order::whereDate('created_at', now())->where('status', 'completed')->sum('total_amount');
        $yesterday = Order::whereDate('created_at', now()->subDay())->where('status', 'completed')->sum('total_amount');
        
        $growth = 0;
        if ($yesterday > 0) {
            $growth = (($today - $yesterday) / $yesterday) * 100;
        } else {
            $growth = $today > 0 ? 100 : 0;
        }

        return [
            'today' => $today,
            'growth_percentage' => round($growth, 1),
            'trend' => $growth >= 0 ? 'up' : 'down'
        ];
    }

    private function getGeneralStats(): array {
        return [
            'users'      => User::count(),
            'books'      => Book::count(),
            'categories' => Category::count(),
            'orders'     => Order::count(),
            'revenue'    => Order::where('status', 'completed')->sum('total_amount'),
            'avgOrder'   => Order::where('status', 'completed')->avg('total_amount') ?? 0,
        ];
    }

    private function getUserGrowth() {
        return User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')->orderBy('date', 'ASC')->get();
    }

    private function getTopBooks() {
        return Book::join('order_items', 'books.id', '=', 'order_items.book_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('books.id', 'books.title', DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_earned'))
            ->groupBy('books.id', 'books.title')->orderByDesc('total_earned')->take(3)->get();
    }

    private function getTopCategories() {
        return Category::join('books', 'categories.id', '=', 'books.category_id')
            ->join('order_items', 'books.id', '=', 'order_items.book_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select('categories.id', 'categories.name', DB::raw('COUNT(order_items.id) as sales_count'))
            ->groupBy('categories.id', 'categories.name')->orderByDesc('sales_count')->take(3)->get();
    }

    private function getLowStockBooks() {
        return Book::where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')
            ->paginate(5, ['*'], 'stock_page');
    }
}