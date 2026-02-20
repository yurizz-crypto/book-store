@extends('layouts.app')

@section('title', 'Admin | Order Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-10">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Order Management</h1>
        <p class="text-gray-500 font-bold tracking-tight">Monitor and update all customer transactions.</p>
    </div>

    {{-- Status Tabs with Fixed Icons --}}
    <div class="flex flex-wrap gap-4 mb-8 bg-gray-100 p-2 rounded-2xl w-max">
        @php
            $statusIcons = [
                'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'processing' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'cancelled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
            ];
        @endphp

        @foreach($statusIcons as $s => $iconPath)
            <a href="{{ route('admin.orders.index', ['status' => $s]) }}" 
               class="flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm transition-all {{ $status === $s ? 'bg-white shadow-md text-indigo-600' : 'text-gray-500 hover:text-indigo-600' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                </svg>
                {{ ucfirst($s) }}
            </a>
        @endforeach
    </div>

    {{-- Orders List --}}
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm hover:shadow-md transition-shadow flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6">
                    {{-- User Avatar Initials --}}
                    <div class="h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xl">
                        {{ substr($order->user->first_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-gray-900">Order #{{ $order->id }}</h3>
                            <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded font-bold uppercase text-gray-500">{{ $order->status }}</span>
                        </div>
                        <p class="text-sm font-bold text-gray-500">Customer: <span class="text-gray-900">{{ $order->user->email }}</span></p>
                        <p class="text-xs text-gray-400 font-medium">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-3">
                    <p class="text-2xl font-black text-indigo-600 tracking-tighter">₱{{ number_format($order->total_amount, 2) }}</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition">Details</a>
                        
                        @if($order->status === 'completed')
                            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">Finalized</span>
                            </div>
                        @else
                            @if ($order->status !== 'cancelled')
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('orders.update', $order) }}" method="POST" class="flex items-center gap-2">
                                        @csrf 
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-[10px] font-black uppercase tracking-widest rounded-xl border-gray-200 focus:ring-indigo-500 py-2 pl-3 pr-8">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Complete</option>
                                        </select>
                                    </form>

                                    <form action="{{ route('orders.update', $order) }}" method="POST">
                                        @csrf 
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" onclick="return confirm('Are you sure you want to decline this order?')" class="p-2 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all duration-300" title="Decline Order">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
                <p class="text-gray-400 font-black tracking-widest ">No {{ $status }} orders to display.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>
@endsection