<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('admin.dashboard.dashboard', $this->dashboardService->getAdminMetrics());
        }

        return view('dashboard', $this->dashboardService->getUserMetrics($user));
    }

    public function exportMyData(): JsonResponse
    {
        $user = Auth::user();
        
        $user->load(['orders.orderItems.book', 'reviews.book']);

        $payload = [
            'account_information' => [
                'user_id'         => $user->id,
                'name'            => $user->first_name . ' ' . $user->last_name,
                'email'           => $user->email,
                'account_created' => $user->created_at->toIso8601String(),
            ],
            'purchase_history' => $user->orders->map(fn($order) => [
                'order_id'     => $order->id,
                'status'       => $order->status,
                'total_amount' => $order->total_amount,
                'date'         => $order->created_at->toIso8601String(),
                'items'        => $order->orderItems->map(fn($item) => [
                    'book_title' => $item->book->title ?? 'Deleted Book',
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->unit_price
                ])
            ]),
            'product_reviews' => $user->reviews->map(fn($review) => [
                'book_title' => $review->book->title ?? 'Deleted Book',
                'rating'     => $review->rating,
                'comment'    => $review->comment,
                'date'       => $review->created_at->toIso8601String(),
            ])
        ];

        $filename = 'my_data_' . now()->format('Ymd_His') . '.json';
        
        return response()->json($payload, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ], JSON_PRETTY_PRINT);
    }
}