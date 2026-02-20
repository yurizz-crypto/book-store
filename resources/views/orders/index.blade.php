@extends('layouts.app')

@section('title', 'Orders | PageTurner')

@section('content')

@php
    $statusIcons = [
        'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'processing' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'cancelled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Order Status</h1>
        <p class="text-gray-500 font-bold tracking-tight">Welcome to your shopping cart.</p>
    </div>

    {{-- Tabbed Navigation --}}
    <div class="flex flex-wrap gap-4 mb-8 bg-gray-100 p-2 rounded-2xl w-max">
        @foreach($statusIcons as $s => $iconPath)
            <a href="{{ route('orders.index', ['status' => $s]) }}" 
            class="flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all {{ $status === $s ? 'bg-white shadow-md text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                </svg>
                {{ ucfirst($s) }}
            </a>
        @endforeach
    </div>

    <div class="space-y-6">
        @forelse($orders as $order)
            @php $firstItem = $order->orderItems->first(); @endphp
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 w-full md:w-auto">
                    {{-- Interactive Book Image --}}
                    <a href="{{ route('books.show', $firstItem->book) }}" class="flex-shrink-0 group relative">
                        <div class="h-24 w-16 bg-gray-100 rounded-lg overflow-hidden shadow-sm group-hover:shadow-md transition-all">
                            @if($firstItem->book->cover_image)
                                <img src="{{ asset('storage/' . $firstItem->book->cover_image) }}" alt="{{ $firstItem->book->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif
                        </div>
                        {{-- Hover Overlay --}}
                        <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/10 transition-colors flex items-center justify-center rounded-lg">
                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                    </a>

                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-gray-900">Order #{{ $order->id }}</h3>
                            @if(Auth::user()->role === 'admin')
                                <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded font-bold uppercase tracking-tighter">{{ $order->user->name }}</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 font-medium">{{ $order->created_at->format('M d, Y') }} • ₱{{ number_format($order->total_amount, 2) }}</p>
                        <a href="{{ route('books.show', $firstItem->book) }}" class="mt-2 block text-xs font-black text-indigo-600 uppercase hover:underline decoration-2 underline-offset-4">
                            {{ $firstItem->book->title }} (x{{ $firstItem->quantity }})
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                    <a href="{{ route('orders.show', $order) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition active:scale-95">Details</a>
                    @if($order->status === 'completed')
                        <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Completed</span>
                        </div>
                    @endif
                    @if(Auth::user()->role === 'admin')
                        {{-- Admin Actions --}}
                        <form action="{{ route('orders.update', $order) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="text-[10px] font-black uppercase tracking-widest rounded-xl border-gray-200 focus:ring-indigo-500 py-2.5 pl-3 pr-8 shadow-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    @elseif($order->status === 'pending')
                        {{-- Customer Cancel --}}
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" onclick="return confirm('Cancel this order?')" class="px-5 py-2.5 bg-gray-100 text-red-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-200 transition active:scale-95">Cancel Order</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                <p class="text-gray-400 font-black uppercase tracking-widest">No {{ $status }} orders found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection