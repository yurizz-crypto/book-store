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

    public function exportMyData()
    {
        $user = Auth::user();
        
        // Eager load relationships to prevent N+1 query problems
        $user->load(['orders.orderItems.book', 'reviews.book']);

        // Construct the GDPR-compliant payload
        $payload = [
            'account_information' => [
                'user_id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'account_created' => $user->created_at->toIso8601String(),
            ],
            'purchase_history' => $user->orders->map(function($order) {
                return [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                    'date' => $order->created_at->toIso8601String(),
                    'items' => $order->orderItems->map(function($item) {
                        return [
                            'book_title' => $item->book ? $item->book->title : 'Deleted Book',
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price
                        ];
                    })
                ];
            }),
            'product_reviews' => $user->reviews->map(function($review) {
                return [
                    'book_title' => $review->book ? $review->book->title : 'Deleted Book',
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'date' => $review->created_at->toIso8601String(),
                ];
            })
        ];

        // Return as a downloadable JSON file
        $filename = 'pageturner_my_data_' . now()->format('Ymd_His') . '.json';
        
        return response()->json($payload, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Type' => 'application/json'
        ], JSON_PRETTY_PRINT);
    }
}