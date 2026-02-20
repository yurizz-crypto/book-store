<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query = Order::where('status', $status)->with('orderItems.book');

        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $orders = $query->latest()->paginate(10);
        return view('orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load('orderItems.book', 'user');
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $book = Book::findOrFail($request->book_id);
        
        $order = Order::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'cart'],
            ['total_amount' => 0]
        );

        $item = $order->orderItems()->where('book_id', $book->id)->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + $request->quantity,
                'unit_price' => $book->price
            ]);
        } else {
            $order->orderItems()->create([
                'book_id' => $book->id,
                'quantity' => $request->quantity,
                'unit_price' => $book->price
            ]);
        }

        $order->load('orderItems');
        
        $order->update([
            'total_amount' => $order->orderItems->sum(function($item) {
                return $item->quantity * $item->unit_price;
            })
        ]);
        
        return redirect()->route('orders.index', ['status' => 'cart'])
            ->with('success', 'Added to cart!');
    }

    public function update(Request $request, Order $order)
    {
        if (!Auth::user()->isAdmin() && $order->status === 'completed') {
            return back()->with('error', 'Completed orders are locked.');
        }

        if (Auth::user()->isAdmin()) {
            $order->update(['status' => $request->status]);
        } elseif ($order->status === 'pending' && $request->status === 'cancelled') {
            $order->update(['status' => 'cancelled']);
        } elseif ($order->status === 'cart' && $request->status === 'cancelled') {
            $order->delete();
        }

        return back()->with('success', 'Order updated!');
    }

    public function checkout(Request $request, Order $order)
    {
        if (!Auth::user()->hasAddress()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please add a shipping address before checking out.');
        }

        $order->update([
            'status' => 'pending',
            'address_id' => Auth::user()->addresses()->where('is_default', true)->first()->id,
        ]);

        return redirect()->route('orders.index', ['status' => 'pending'])
            ->with('success', 'Order placed! Please wait for confirmation.');
    }

    public function adminIndex(Request $request)
    {
        $status = $request->query('status', 'pending');

        $orders = Order::where('status', $status)
            ->where('status', '!=', 'cart')
            ->with(['user', 'orderItems.book'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status'));
    }
}