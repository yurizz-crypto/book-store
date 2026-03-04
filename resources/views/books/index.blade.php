@extends('layouts.app')

@section('title', 'All Books - PageTurner')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
        <h1 class="text-5xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">Our Collection</h1>
        <p class="text-[#0046FF]/60 mt-3 text-xl font-bold italic">Discover your next favorite story.</p>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    
        <div class="mb-12">
            <form action="{{ route('books.index') }}" method="GET" class="relative">
                <div class="bg-white rounded-[2rem] shadow-2xl shadow-[#001BB7]/5 border border-[#0046FF]/10 p-2 flex flex-col lg:flex-row items-center gap-3">
                    
                    <div class="relative flex-1 w-full group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#0046FF] transition-colors group-focus-within:text-[#FF8040]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-14 pr-4 py-4 border-transparent focus:border-transparent focus:ring-0 text-[#001BB7] font-bold placeholder-[#001BB7]/30 sm:text-sm bg-transparent rounded-2xl" 
                            placeholder="Search titles, authors, or ISBN...">
                    </div>

                    {{-- Vertical Separators using Cream --}}
                    <div class="hidden lg:block h-10 w-px bg-[#F5F1DC]"></div>

                    {{-- Category Dropdown --}}
                    <div class="w-full lg:w-56">
                        <select name="category" onchange="this.form.submit()" 
                                class="block w-full py-4 pl-6 pr-10 border-transparent focus:border-transparent focus:ring-0 text-[#001BB7] font-black uppercase tracking-widest text-[10px] bg-transparent cursor-pointer hover:text-[#0046FF] transition appearance-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hidden lg:block h-10 w-px bg-[#F5F1DC]"></div>

                    {{-- Sort Dropdown --}}
                    <div class="w-full lg:w-56">
                        <select name="sort" onchange="this.form.submit()" 
                                class="block w-full py-4 pl-6 pr-10 border-transparent focus:border-transparent focus:ring-0 text-[#001BB7] font-black uppercase tracking-widest text-[10px] bg-transparent cursor-pointer hover:text-[#0046FF] transition appearance-none">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    {{-- High-Energy Search Button --}}
                    <button type="submit" class="w-full lg:w-auto bg-[#001BB7] hover:bg-[#0046FF] text-[#F5F1DC] px-12 py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-xl shadow-[#001BB7]/20 active:scale-95">
                        Search
                    </button>
                </div>

                {{-- Filter Status with Sunset Orange --}}
                @if(request()->anyFilled(['search', 'category']))
                    <div class="flex items-center gap-4 mt-6 ml-4">
                        <span class="text-[9px] font-black text-[#001BB7]/40 uppercase tracking-[0.3em]">Filtered View</span>
                        <a href="{{ route('books.index') }}" class="px-3 py-1 bg-[#FF8040]/10 text-[#FF8040] text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-[#FF8040] hover:text-white transition-all flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear All
                        </a>
                    </div>
                @endif
            </form>
        </div>

    {{-- Results Grid --}}
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
        <div class="mt-20 pt-10 border-t border-[#F5F1DC]">
            {{ $books->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-32 bg-[#F5F1DC]/30 rounded-[3rem] border-2 border-dashed border-[#0046FF]/10">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <svg class="w-10 h-10 text-[#0046FF]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="text-[#001BB7] font-black uppercase tracking-widest">No books found</h3>
            <p class="text-[#0046FF]/60 font-bold mt-2">Try adjusting your search terms or genre filter.</p>
            <a href="{{ route('books.index') }}" class="mt-8 inline-block text-[10px] font-black uppercase tracking-[0.2em] text-[#0046FF] border-b-2 border-[#0046FF] pb-1 hover:text-[#FF8040] hover:border-[#FF8040] transition-all">
                Reset Collection
            </a>
        </div>
    @endif
</div>
@endsection