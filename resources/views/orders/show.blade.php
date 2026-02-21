@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4" id="printable-order">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-[#001BB7]/5 border border-[#0046FF]/10 relative overflow-hidden">
        {{-- Decorative Brand Accent --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-[#F5F1DC] rounded-bl-[5rem] -mr-10 -mt-10 z-0"></div>

        <div class="relative z-10">
            <div class="flex justify-between items-start mb-12">
                <div>
                    <h1 class="text-4xl font-black uppercase tracking-tighter text-[#001BB7]">Invoice</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[10px] font-black uppercase tracking-widest text-[#0046FF] bg-[#0046FF]/10 px-2 py-0.5 rounded">Order #{{ $order->id }}</span>
                        <span class="text-xs font-bold text-gray-400">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <button onclick="window.print()" class="print:hidden bg-[#001BB7] text-[#F5F1DC] px-6 py-3 rounded-xl font-black uppercase tracking-widest text-xs transition-all hover:bg-[#0046FF] shadow-lg active:scale-95">
                    Print Receipt
                </button>
            </div>

            {{-- Items Table --}}
            <div class="space-y-4 mb-10">
                <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-[#0046FF]/10 pb-2">
                    <span>Description</span>
                    <span>Subtotal</span>
                </div>
                
                @foreach($order->orderItems as $item)
                    <div class="group">
                        <a href="{{ route('books.show', $item->book->id) }}" class="flex justify-between items-center py-2">
                            <div class="flex flex-col">
                                <span class="font-black text-[#001BB7] group-hover:text-[#FF8040] transition-colors uppercase tracking-tight">{{ $item->book->title }}</span>
                                <span class="text-xs font-bold text-gray-400">Qty: {{ $item->quantity }} @ ₱{{ number_format($item->unit_price, 2) }}</span>
                            </div>
                            <span class="font-mono font-bold text-[#001BB7]">₱ {{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="pt-6 border-t-4 border-[#001BB7]">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">Total Amount</span>
                    <span class="text-4xl font-black text-[#FF8040] tracking-tighter">₱ {{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="mt-12 text-center">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-300">Thank you for choosing PageTurner.</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        nav, footer, .print\:hidden { display: none !important; }
        body { background: white !important; }
        #printable-order { margin: 0; width: 100%; max-width: 100%; padding: 0; }
        .rounded-\[2\.5rem\] { border-radius: 0 !important; }
        .shadow-2xl { shadow: none !important; }
    }
</style>
@endsection