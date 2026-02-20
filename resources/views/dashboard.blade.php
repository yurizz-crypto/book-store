@extends('layouts.app') {{-- Matches your file path --}}

@section('title', 'Dashboard - PageTurner')

@section('header')
    <div class="flex items-center justify-between w-full">
        <span class="font-black text-[20px] tracking-tight">Dashboard</span>
        <div class="badge badge-primary font-bold px-4 py-3 uppercase tracking-widest text-[10px]">
            {{ Auth::user()->role }} Account
        </div>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="bg-base-100 shadow-2xl shadow-indigo-100/50 rounded-[2.5rem] border border-base-200 overflow-hidden">
            <div class="p-10 flex flex-col md:flex-row items-center gap-8">
                {{-- Avatar Initial --}}
                <div class="avatar placeholder">
                    <div class="flex items-center justify-center bg-indigo-600 text-white rounded-2xl w-24 h-24 shadow-lg shadow-indigo-200">
                        <span class="text-4xl font-black">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
                    </div>
                </div>

                <div class="text-center md:text-left">
                    <h1 class="text-3xl font-black text-base-content tracking-tight">
                        Welcome back, {{ Auth::user()->getFullName() }}!
                    </h1>
                    <p class="text-base-content/60 mt-2 font-medium">
                        Manage your PageTurner orders, reviews, and profile settings here.
                    </p>
                </div>
            </div>

            {{-- Quick Actions Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 border-t border-base-200">
                <a href="{{ route('books.index') }}" class="p-8 text-center hover:bg-base-200 transition-colors group">
                    <div class="text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-base-content">Browse Catalog</span>
                </a>

                @if (!auth()->user()->isAdmin())
                    <a href="{{ route('orders.index') }}" class="p-8 text-center hover:bg-base-200 transition-colors group border-x border-base-200">
                        <div class="text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest text-base-content">My Orders</span>
                    </a>
                @else
                    <a href="{{ route('admin.orders.index') }}" class="p-8 text-center hover:bg-base-200 transition-colors group border-x border-base-200">
                        <div class="text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <span class="text-xs font-black uppercase tracking-widest text-base-content">Manage Orders</span>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="p-8 text-center hover:bg-base-200 transition-colors group">
                    <div class="text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-base-content">Account Settings</span>
                </a>
            </div>
        </div>
    </div>
@endsection