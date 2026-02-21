@extends('layouts.app')

@section('title', 'Admin | Order Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-5xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">Order Registry</h1>
            <p class="text-[#0046FF]/60 font-bold mt-2 text-lg">Monitor and fulfill your library's transactions.</p>
        </div>
        
        {{-- Status Tabs: The Navigation Dock --}}
        <div class="flex flex-wrap gap-2 bg-[#F5F1DC]/50 p-1.5 rounded-[2rem] border border-[#0046FF]/5 shadow-inner">
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
                   class="flex items-center gap-2 px-5 py-2.5 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest transition-all {{ $status === $s ? 'bg-[#001BB7] text-white shadow-lg' : 'text-[#001BB7]/50 hover:text-[#001BB7] hover:bg-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                    </svg>
                    {{ $s }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Orders List --}}
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-[2.5rem] border border-[#0046FF]/5 p-8 shadow-sm hover:shadow-xl hover:shadow-[#001BB7]/5 transition-all flex flex-col md:flex-row justify-between items-center gap-8 group">
                <div class="flex items-center gap-8">
                    {{-- Avatar Initials in Navy/Cream --}}
                    <div class="h-16 w-16 rounded-[1.5rem] bg-[#F5F1DC] text-[#001BB7] flex items-center justify-center font-black text-2xl shadow-inner group-hover:bg-[#001BB7] group-hover:text-white transition-colors duration-500">
                        {{ substr($order->user->first_name, 0, 1) }}
                    </div>
                    
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">Order #{{ $order->id }}</h3>
                            <span class="text-[9px] bg-[#F5F1DC] px-3 py-1 rounded-full font-black uppercase tracking-widest text-[#0046FF]/70 border border-[#0046FF]/10">
                                {{ $order->status }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-[#001BB7]/60">Customer: <span class="text-[#001BB7]">{{ $order->user->email }}</span></p>
                        <p class="text-[10px] text-[#0046FF]/40 font-black uppercase tracking-[0.1em] mt-1">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                    </div>
                </div>

                <div class="flex flex-col md:items-end gap-4">
                    <p class="text-4xl font-black text-[#001BB7] tracking-tighter leading-none">₱{{ number_format($order->total_amount, 2) }}</p>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('orders.show', $order) }}" class="px-6 py-2.5 bg-[#F5F1DC]/50 text-[#001BB7] rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#001BB7] hover:text-white transition-all shadow-sm">Details</a>
                        
                        @if($order->status === 'completed')
                            <div class="flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-[9px] font-black uppercase tracking-widest">Finalized</span>
                            </div>
                        @else
                            @if ($order->status !== 'cancelled')
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex items-center">
                                        @csrf @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-[9px] font-black uppercase tracking-widest rounded-xl border-[#0046FF]/10 bg-white focus:ring-[#0046FF] py-2.5 pl-4 pr-10 text-[#001BB7]">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Complete</option>
                                        </select>
                                    </form>

                                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" onclick="return confirm('Decline this order?')" class="p-2.5 bg-[#FF8040]/10 text-[#FF8040] rounded-xl hover:bg-[#FF8040] hover:text-white transition-all duration-300 shadow-sm" title="Decline Order">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-32 bg-[#F5F1DC]/30 rounded-[3rem] border-2 border-dashed border-[#001BB7]/10">
                <p class="text-[#001BB7]/40 font-black uppercase tracking-[0.3em]">No {{ $status }} orders discovered.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $orders->links() }}
    </div>
</div>
@endsection