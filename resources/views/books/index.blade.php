@extends('layouts.app')

@section('title', 'All Books - PageTurner')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Our Collection</h1>
        <p class="text-gray-500 mt-2 text-lg">Discover your next favorite story.</p>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 rounded-lg">
    
        <div class="mb-10">
            <form action="{{ route('books.index') }}" method="GET" class="relative">
                <div class="bg-white rounded-2xl shadow-xl shadow-indigo-100/40 border border-gray-100 p-2 flex flex-col md:flex-row items-center gap-3">
                    
                    {{-- Search --}}
                    <div class="relative flex-1 w-full group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-indigo-500 transition-colors group-focus-within:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-11 pr-4 py-3.5 border-transparent focus:border-transparent focus:ring-0 text-gray-900 placeholder-gray-400 sm:text-sm bg-transparent rounded-xl" 
                            placeholder="Search by title, author, or ISBN...">
                    </div>

                    <div class="hidden md:block h-8 w-px bg-gray-200/60"></div>

                    {{-- Category --}}
                    <div class="w-full md:w-48">
                        <select name="category" onchange="this.form.submit()" 
                                class="block w-full py-3.5 pl-4 pr-10 border-transparent focus:border-transparent focus:ring-0 text-gray-600 sm:text-sm bg-transparent cursor-pointer hover:text-indigo-600 transition appearance-none">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort --}}
                    <div class="w-full md:w-48">
                        <select name="sort" onchange="this.form.submit()" 
                                class="block w-full py-3.5 pl-4 pr-10 border-transparent focus:border-transparent focus:ring-0 text-gray-600 sm:text-sm bg-transparent cursor-pointer hover:text-indigo-600 transition appearance-none">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-3.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-indigo-100 active:scale-95">
                        Search
                    </button>
                </div>

                {{-- Filter Status --}}
                @if(request()->anyFilled(['search', 'category']))
                    <div class="flex items-center gap-3 mt-4 ml-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Active Filters</span>
                        <a href="{{ route('books.index') }}" class="text-xs text-indigo-500 hover:text-red-500 font-bold flex items-center gap-1 transition-colors">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                            Clear All
                        </a>
                    </div>
                @endif
            </form>
        </div>

    {{-- Results --}}
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
        <div class="mt-16">
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center py-24 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
            <p class="text-gray-400 font-medium">No books found. Try a different search term!</p>
        </div>
    @endif
</div>
@endsection