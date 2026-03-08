<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $userGrowth = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
            $avgOrder = Order::where('status', 'completed')->avg('total_amount') ?? 0;

            return view('admin.dashboard', [
                'stats' => [
                    'users'      => User::count(),
                    'books'      => Book::count(),
                    'categories' => Category::count(),
                    'orders'     => Order::count(),
                    'revenue'    => $totalRevenue,
                    'avgOrder'   => $avgOrder,
                ],
                'recentOrders'  => Order::with('user')->latest()->take(6)->get(),
                'statusSummary' => Order::select('status', DB::raw('count(*) as total'))
                                    ->groupBy('status')->get(),
                'recentReviews' => Review::with(['user', 'book'])->latest()->take(4)->get(),
                'userGrowth'    => $userGrowth,
            ]);
        }

        return view('dashboard', [
            'totalOrders'  => $user->orders()->count(),
            'recentOrders' => $user->orders()->latest()->take(5)->get(),
            'recentBooks'  => Book::whereHas('orderItems.order', function($q) use ($user) {
                                $q->where('user_id', $user->id)->where('status', 'completed');
                             })->latest()->take(4)->get(),
        ]);
    }
}