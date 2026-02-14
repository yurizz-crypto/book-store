@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4" id="printable-order">
    <div class="bg-white p-10 rounded-3xl shadow-xl border border-gray-100">
        <div class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter text-indigo-600">Invoice</h1>
                <p class="text-gray-500">Order #{{ $order->id }}</p>
            </div>
            <button onclick="window.print()" class="print:hidden bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold">Print Receipt</button>
        </div>

        <div class="border-t border-b border-gray-100 py-6 mb-6">
            @foreach($order->orderItems as $item)
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-800">{{ $item->book->title }} (x{{ $item->quantity }})</span>
                    <span class="font-mono">₱ {{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between items-center text-2xl font-black">
            <span>Total</span>
            <span class="text-indigo-600">₱ {{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>
</div>

<style>
    @media print {
        nav, .print\:hidden { display: none !important; }
        body { background: white; }
        #printable-order { margin: 0; width: 100%; max-width: 100%; }
    }
</style>
@endsection