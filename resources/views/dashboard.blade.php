@extends('layouts.app')

@section('title', 'Dashboard - PageTurner')

@section('header')
    <div class="flex items-center justify-between w-full">
        <span class="font-black text-[20px] tracking-tighter uppercase text-[#001BB7]">Dashboard</span>
        <div class="bg-[#FF8040] text-white font-black px-4 py-2 rounded-lg uppercase tracking-widest text-[10px] shadow-sm">
            {{ Auth::user()->role }} Account
        </div>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="bg-white shadow-2xl shadow-[#0046FF]/10 rounded-[2.5rem] border border-[#0046FF]/5 overflow-hidden">
            {{-- Welcome Header --}}
            <div class="p-10 flex flex-col md:flex-row items-center gap-8 bg-gradient-to-br from-white to-[#F5F1DC]/30">
                {{-- Avatar Initial --}}
                <div class="avatar placeholder">
                    <div class="flex items-center justify-center bg-[#001BB7] text-[#F5F1DC] rounded-3xl w-24 h-24 shadow-xl shadow-[#001BB7]/20">
                        <span class="text-4xl font-black">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-black text-[#001BB7] tracking-tighter uppercase">
                        Welcome back, {{ Auth::user()->getFullName() }}!
                    </h1>
                    <p class="text-[#0046FF] mt-2 font-bold opacity-70">
                        Manage your PageTurner orders, reviews, and profile settings here.
                    </p>
                </div>
            </div>

            {{-- Quick Actions Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 border-t border-[#0046FF]/10">
                {{-- Browse Catalog --}}
                <a href="{{ route('books.index') }}" class="p-10 text-center hover:bg-[#F5F1DC]/50 transition-all group border-b md:border-b-0 border-[#0046FF]/10">
                    <div class="text-[#0046FF] mb-3 group-hover:scale-110 group-hover:text-[#FF8040] transition-all duration-300">
                        <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-[#001BB7]">Browse Catalog</span>
                </a>

                {{-- Conditional Order Management --}}
                @if (!Auth::user()->isAdmin())
                    <a href="{{ route('orders.index', 'status=cart') }}" class="p-10 text-center hover:bg-[#F5F1DC]/50 transition-all group border-b md:border-b-0 md:border-x border-[#0046FF]/10">
                        <div class="text-[#0046FF] mb-3 group-hover:scale-110 group-hover:text-[#FF8040] transition-all duration-300">
                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest text-[#001BB7]">My Orders</span>
                    </a>
                @else
                    <a href="{{ route('admin.orders.index') }}" class="p-10 text-center hover:bg-[#F5F1DC]/50 transition-all group border-b md:border-b-0 md:border-x border-[#0046FF]/10">
                        <div class="text-[#0046FF] mb-3 group-hover:scale-110 group-hover:text-[#FF8040] transition-all duration-300">
                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest text-[#001BB7]">Manage Orders</span>
                    </a>
                @endif

                {{-- Account Settings --}}
                <a href="{{ route('profile.edit') }}" class="p-10 text-center hover:bg-[#F5F1DC]/50 transition-all group">
                    <div class="text-[#0046FF] mb-3 group-hover:scale-110 group-hover:text-[#FF8040] transition-all duration-300">
                        <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-[#001BB7]">Account Settings</span>
                </a>
            </div>
        </div>
    </div>
@endsection