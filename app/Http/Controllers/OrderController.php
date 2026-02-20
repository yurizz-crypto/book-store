<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $query = Order::where('status', $status)->with('orderItems.book');

        if (Auth::user()->role !== 'admin') {
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
        
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $book->price * $request->quantity,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'quantity' => $request->quantity,
            'unit_price' => $book->price,
        ]);

        return redirect()->route('orders.index', ['status' => 'pending'])
            ->with('success', 'Order placed successfully!');
    }

    public function update(Request $request, Order $order)
    {
        if ($order->status === 'completed') {
            return back()->with('error', 'Completed orders are locked and cannot be modified.');
        }

        if (Auth::user()->role === 'admin') {
            $order->update(['status' => $request->status]);
        } elseif ($order->status === 'pending' && $request->status === 'cancelled') {
            $order->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function adminIndex(Request $request)
    {
        $status = $request->query('status', 'pending');

        $orders = Order::where('status', $status)
            ->with(['user', 'orderItems.book'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status'));
    }
}