<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $orders = Order::where('user_id', Auth::id())
            ->where('status', $status)
            ->with('orderItems.book') // Prevents N+1
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders', 'status'));
    }

    public function adminIndex(Request $request)
    {
        $status = $request->query('status', 'pending');

        $orders = Order::where('status', $status)
            ->where('status', '!=', 'cart')
            ->with(['user', 'orderItems.book']) // Prevents N+1
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        
        $order->load(['orderItems.book', 'user']);
        
        return view('orders.show', compact('order'));
    }

    public function store(StoreCartItemRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        if ($book->stock_quantity < $request->quantity) {
            return back()->with('error', "Only {$book->stock_quantity} units left in stock.");
        }

        DB::transaction(function () use ($request, $book) {
            $order = Order::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'cart'],
                ['total_amount' => 0]
            );

            $item = $order->orderItems()->where('book_id', $book->id)->first();

            if ($item) {
                $item->increment('quantity', $request->quantity);
                $item->update(['unit_price' => $book->price]); // Sync price in case it changed
            } else {
                $order->orderItems()->create([
                    'book_id'    => $book->id,
                    'quantity'   => $request->quantity,
                    'unit_price' => $book->price
                ]);
            }

            // PERFORMANCE FIX: Calculate sum directly in the database, don't load models into memory
            $newTotal = $order->orderItems()->sum(DB::raw('quantity * unit_price'));
            
            $order->update(['total_amount' => $newTotal]);
        });
        
        return redirect()->route('orders.index', ['status' => 'cart'])
            ->with('success', 'Added to cart!');
    }

    public function checkout(Request $request, Order $order)
    {
        if (!Auth::user()->hasAddress()) {
            return redirect()->route('profile.edit')->with('error', 'Please add a shipping address.');
        }

        DB::transaction(function () use ($order) {
            // Lock the rows for updating to prevent race conditions (double-selling stock)
            $items = $order->orderItems()->with('book')->lockForUpdate()->get();

            foreach ($items as $item) {
                $book = $item->book;
                if ($book->stock_quantity < $item->quantity) {
                    throw new \Exception("The book '{$book->title}' is now out of stock.");
                }
                
                $book->decrement('stock_quantity', $item->quantity);
            }

            $order->update([
                'status'     => 'pending',
                'address_id' => Auth::user()->addresses()->where('is_default', true)->first()->id,
            ]);

            event(new OrderPlaced($order));
        });

        return redirect()->route('orders.index', ['status' => 'pending'])
            ->with('success', 'Order placed successfully!');
    }
    
    public function update(UpdateOrderStatusRequest $request, Order $order)
    {
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // 1. Handle Admin Status Updates
        if (Auth::user()->isAdmin()) {
            $order->update(['status' => $newStatus]);
            return back()->with('success', 'Status updated and customer notified.');
        }

        // 2. Handle User clearing their cart
        if ($oldStatus === 'cart' && $newStatus === 'cancelled') {
            $order->delete();
            return back()->with('success', 'Cart cleared.');
        }

        // 3. Handle User cancelling an active order
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            DB::transaction(function () use ($order) {
                foreach ($order->orderItems()->with('book')->get() as $item) {
                    $item->book->increment('stock_quantity', $item->quantity);
                }
                $order->update(['status' => 'cancelled']); 
            });
            return back()->with('success', 'Order cancelled and stock restored.');
        }

        return back()->with('error', 'Unauthorized action.');
    }
}