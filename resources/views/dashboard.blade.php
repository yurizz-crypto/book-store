@extends('layouts.app')

@section('title', 'My Dashboard - PageTurner')

@section('header')
    <div class="flex items-center justify-between w-full">
        <span class="font-black text-[20px] tracking-tighter uppercase text-[#001BB7]">Dashboard</span>
        <div class="flex gap-3">
            <div class="{{ Auth::user()->hasVerifiedEmail() ? 'bg-emerald-500' : 'bg-amber-500' }} text-white font-black px-4 py-2 rounded-lg uppercase tracking-widest text-[9px] shadow-sm">
                {{ Auth::user()->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
            </div>
            <div class="{{ Auth::user()->two_factor_enabled ? 'bg-[#001BB7]' : 'bg-[#FF8040]' }} text-white font-black px-4 py-2 rounded-lg uppercase tracking-widest text-[9px] shadow-sm">
                2FA: {{ Auth::user()->two_factor_enabled ? 'ON' : 'OFF' }}
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="pb-10 pt-2 space-y-8">
        
        <div class="bg-white shadow-2xl shadow-[#0046FF]/10 rounded-[2.5rem] border border-[#0046FF]/5 overflow-hidden">
            <div class="p-10 flex flex-col md:flex-row items-center gap-8 bg-gradient-to-br from-white to-[#F5F1DC]/30">
                <div class="flex items-center justify-center bg-[#001BB7] text-[#F5F1DC] rounded-3xl w-24 h-24 shadow-xl shadow-[#001BB7]/20">
                    <span class="text-4xl font-black">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
                </div>
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl font-black text-[#001BB7] tracking-tighter uppercase">Welcome, {{ Auth::user()->first_name }}!</h1>
                    <p class="text-[#0046FF]/60 font-bold mt-1">Reader since {{ Auth::user()->created_at->format('M Y') }}</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                    <div class="bg-white px-8 py-4 rounded-2xl border border-[#0046FF]/10 shadow-sm text-center flex-1">
                        <p class="text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40 mb-1">Total Orders</p>
                        <p class="text-3xl font-black text-[#001BB7]">{{ $totalOrders }}</p>
                    </div>

                    <div class="bg-[#001BB7] px-8 py-4 rounded-2xl shadow-lg text-center flex-1">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/50 mb-1">Total Spent</p>
                        <div class="flex items-baseline justify-center gap-1 text-white">
                            <span class="text-sm font-black text-[#FF8040]">₱</span>
                            <p class="text-3xl font-black tracking-tighter">{{ number_format($totalSpent, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 border-t border-[#0046FF]/10">
                <a href="{{ route('books.index') }}" class="p-8 text-center hover:bg-[#F5F1DC]/50 transition-all group border-b md:border-b-0 md:border-r border-[#0046FF]/10">
                    <div class="text-[#0046FF] mb-3 group-hover:text-[#FF8040] group-hover:scale-110 transition-all duration-300">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#001BB7]">Browse Books</span>
                </a>

                <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="p-8 text-center hover:bg-[#F5F1DC]/50 transition-all group border-b md:border-b-0 md:border-r border-[#0046FF]/10">
                    <div class="text-[#0046FF] mb-3 group-hover:text-[#FF8040] group-hover:scale-110 transition-all duration-300">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#001BB7]">Order History</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="p-8 text-center hover:bg-[#F5F1DC]/50 transition-all group">
                    <div class="text-[#0046FF] mb-3 group-hover:text-[#FF8040] group-hover:scale-110 transition-all duration-300">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#001BB7]">Profile & Security</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-[2.5rem] border border-[#0046FF]/5 shadow-xl">
                <h3 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter mb-6">Recent Orders</h3>
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="flex items-center gap-3">
                            {{-- View Order Details Link --}}
                            <a href="{{ route('orders.show', $order) }}" class="flex-1 block group">
                                <div class="flex items-center justify-between p-4 bg-[#F5F1DC]/30 rounded-2xl border border-transparent group-hover:border-[#0046FF]/10 group-hover:scale-[1.02] transition-all duration-300 shadow-sm group-hover:shadow-md">
                                    <div>
                                        <p class="font-black text-[#001BB7] text-sm group-hover:text-[#0046FF] transition-colors">Order #{{ $order->id }}</p>
                                        <p class="text-[10px] font-bold text-[#0046FF]/60 uppercase tracking-widest">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-white rounded-lg text-[9px] font-black uppercase tracking-widest text-[#001BB7] border border-[#001BB7]/10 group-hover:border-[#FF8040] group-hover:text-[#FF8040] transition-all">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </a>

                            {{--  Download PDF Invoice Button --}}
                            <a href="{{ route('orders.invoice', $order) }}" 
                               class="flex items-center justify-center w-14 h-14 bg-white rounded-2xl border border-[#0046FF]/10 shadow-sm hover:bg-[#001BB7] hover:border-[#001BB7] text-[#001BB7] hover:text-white transition-all duration-300 group"
                               title="Download PDF Invoice">
                                <svg class="w-6 h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <p class="text-sm font-bold text-[#001BB7]/40 italic">No orders yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-[#0046FF]/5 shadow-xl">
                <h3 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter mb-6">Recently Purchased</h3>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($recentBooks as $book)
                        <a href="{{ route('books.show', $book) }}" class="group block p-4 rounded-[2rem] border border-transparent hover:border-[#001BB7]/10 hover:scale-[1.05] transition-all duration-300 shadow-sm hover:shadow-md text-center">
                            @if($book->cover_image)
                                <div class="aspect-[3/4] rounded-xl border border-[#001BB7]/5 overflow-hidden mb-3 shadow-inner">
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                </div>
                            @else
                                <div class="aspect-[3/4] rounded-xl bg-[#F5F1DC]/30 border border-[#001BB7]/5 flex items-center justify-center mb-3 shadow-inner">
                                    <svg class="w-1/2 h-1/2 text-[#0046FF]/20 group-hover:text-[#FF8040]/30 transition-all duration-500 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            @endif
                            <p class="text-[10px] font-black text-[#001BB7] uppercase truncate">{{ $book->title }}</p>
                        </a>
                    @empty
                        <p class="col-span-2 text-sm font-bold text-[#001BB7]/40 italic">Library is empty.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white p-10 rounded-[3rem] border border-[#0046FF]/5 shadow-2xl">
            <h3 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter mb-8">Your Review Activity</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse(Auth::user()->reviews()->latest()->take(4)->get() as $review)
                    <div class="group relative p-8 bg-[#F5F1DC]/30 rounded-[2.5rem] border border-transparent hover:border-[#0046FF]/10 hover:scale-[1.02] transition-all duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex text-[#FF8040]">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $review->rating ? 'fill-current' : 'text-[#001BB7]/10' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <a href="{{ route('books.show', $review->book) }}" class="bg-white text-[#001BB7] p-2 rounded-xl shadow-sm hover:bg-[#001BB7] hover:text-white transition-all opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                        <p class="text-xs font-black text-[#001BB7] uppercase mb-2 tracking-widest">{{ $review->book->title }}</p>
                        <p class="text-[#001BB7]/70 font-bold italic text-sm leading-relaxed line-clamp-2">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <p class="text-sm font-bold text-[#001BB7]/40 italic">You haven't shared any reviews yet.</p>
                @endforelse
            </div>
        </div>

        <div class="p-6 bg-white border border-[#001BB7]/10 rounded-[2rem] shadow-sm flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-[#001BB7] uppercase tracking-tighter">Data Portability</h3>
                <p class="text-sm text-gray-500 font-medium mt-1">Download a copy of your personal data, purchase history, and reviews in JSON format (GDPR Compliant).</p>
            </div>
            <a href="{{ route('dashboard.export-data') }}" class="inline-flex items-center gap-2 bg-[#001BB7] text-white px-6 py-3 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-[#0046FF] transition-colors shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export My Data
            </a>
        </div>
        </div>
@endsection