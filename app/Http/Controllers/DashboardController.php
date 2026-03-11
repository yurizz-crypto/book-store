<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $data = $this->dashboardService->getAdminMetrics();
            
            return view('admin.dashboard.dashboard', $data);
        }

        return view('dashboard', [
            'totalOrders'  => $user->orders()->where('status', '!=', 'cart')->count(),
            'totalSpent'   => $user->orders()->where('status', 'completed')->sum('total_amount'),
            'recentOrders' => $user->orders()->where('status', '!=', 'cart')->latest()->take(5)->get(),
            
            'recentBooks'  => Book::whereHas('orderItems.order', function($q) use ($user) {
                                $q->where('user_id', $user->id)->where('status', 'completed');
                            })->latest()->take(4)->get(),
        ]);
    }
}