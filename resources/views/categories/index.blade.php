@extends('layouts.app')

@section('title', 'Categories - PageTurner')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8 flex justify-between items-end">
        <div>
            <h1 class="text-5xl font-black text-[#001BB7] tracking-tighter uppercase">Categories</h1>
            <p class="text-[#0046FF] font-bold mt-2 text-lg opacity-70">Manage your book genres and classifications.</p>
        </div>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.categories.create') }}" class="bg-[#FF8040] hover:bg-[#ff9663] text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-[#FF8040]/20 active:scale-95">
                    Add Category
                </a>
            @endif
        @endauth
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="space-y-4">
        @foreach($categories as $category)
            <div class="group relative bg-white border border-[#0046FF]/10 rounded-[2rem] shadow-sm hover:shadow-xl hover:shadow-[#001BB7]/5 hover:border-[#0046FF]/30 transition-all duration-300">
                {{-- Entire Row Clickable --}}
                <a href="{{ route('categories.show', $category) }}" class="absolute inset-0 z-10 w-full h-full"></a>
                
                <div class="relative z-20 p-8 flex items-center justify-between pointer-events-none">
                    <div class="flex items-center space-x-8">
                        {{-- Icon Initial --}}
                        <div class="flex-shrink-0 w-14 h-14 bg-[#F5F1DC] text-[#001BB7] rounded-2xl flex items-center justify-center font-black text-2xl group-hover:bg-[#001BB7] group-hover:text-[#F5F1DC] transition-all duration-300 shadow-inner">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-[#001BB7] uppercase tracking-tighter group-hover:text-[#0046FF] transition-colors leading-none">
                                {{ $category->name }}
                            </h3>
                            <p class="text-[#0046FF]/60 text-sm font-medium mt-2 max-w-xl truncate">
                                {{ $category->description ?? 'Explore curated titles in this literary genre.' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-10 pointer-events-auto">
                        {{-- Book Count --}}
                        <div class="text-center hidden md:block">
                            <span class="block text-3xl font-black text-[#001BB7] tracking-tighter">{{ $category->books_count }}</span>
                            <span class="text-[10px] uppercase tracking-[0.2em] text-[#FF8040] font-black">Books</span>
                        </div>

                        {{-- Admin Actions Component --}}
                        <x-admin-actions 
                            label="Category"
                            :editRoute="route('admin.categories.edit', $category)" 
                            :deleteRoute="route('admin.categories.destroy', $category)" 
                        />

                        {{-- Arrow Indicator --}}
                        <div class="text-[#0046FF]/20 group-hover:text-[#FF8040] transform group-hover:translate-x-2 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="4" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-16 pt-10 border-t border-[#0046FF]/10">
        {{ $categories->links() }}
    </div>
</div>
@endsection