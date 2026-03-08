<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Order;
use App\Models\Book;
use App\Models\User;
use App\Notifications\NewOrderReceived;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Policies\OrderPolicy;

class OrderController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $orders = Order::where('user_id', Auth::id())
            ->where('status', $status)
            ->with('orderItems.book')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
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

        // Check if enough stock exists before adding to cart
        if ($book->stock_quantity < $request->quantity) {
            return back()->with('error', "Only {$book->stock_quantity} units left in stock.");
        }

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
            'total_amount' => $order->orderItems->sum(fn($i) => $i->quantity * $i->unit_price)
        ]);
        
        return redirect()->route('orders.index', ['status' => 'cart'])->with('success', 'Added to cart!');
    }

    public function checkout(Request $request, Order $order)
    {
        if (!Auth::user()->hasAddress()) {
            return redirect()->route('profile.edit')->with('error', 'Please add a shipping address.');
        }

        return DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                $book = $item->book;
                if ($book->stock_quantity < $item->quantity) {
                    throw new \Exception("The book '{$book->title}' is now out of stock.");
                }
                
                $book->decrement('stock_quantity', $item->quantity);
            }

            $order->update([
                'status' => 'pending',
                'address_id' => Auth::user()->addresses()->where('is_default', true)->first()->id,
            ]);

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewOrderReceived($order));

            return redirect()->route('orders.index', ['status' => 'pending'])
                ->with('success', 'Order placed successfully!');
        });
    }

    public function update(Request $request, Order $order)
    {
        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled' && $oldStatus !== 'cart') {
            DB::transaction(function () use ($order) {
                foreach ($order->orderItems as $item) {
                    $item->book->increment('stock_quantity', $item->quantity);
                }
                $order->update(['status' => 'cancelled']);
            });
            return back()->with('success', 'Order cancelled and stock restored.');
        }

        if (Auth::user()->isAdmin()) {
            $order->update(['status' => $request->status]);

            $order->load(['orderItems.book', 'user.addresses']); 

            $order->user->notify(new OrderStatusUpdated($order));

            return back()->with('success', 'Status updated and customer notified.');
        }

        if ($oldStatus === 'cart' && $newStatus === 'cancelled') {
            $order->delete();
            return back()->with('success', 'Cart cleared.');
        }

        return back()->with('error', 'Unauthorized action.');
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