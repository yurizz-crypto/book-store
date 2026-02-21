@extends('layouts.app')

@section('title', 'PageTurner - Online Bookstore')

@section('content')
    {{-- Hero Section --}}
    <div class="relative bg-[#001BB7] text-[#F5F1DC] rounded-3xl p-10 mb-12 overflow-hidden shadow-2xl">
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-6xl font-black mb-4 tracking-tighter uppercase">Welcome to PageTurner</h1>
            <p class="text-xl text-[#F5F1DC]/80 mb-8 leading-relaxed font-medium">
                Discover your next favorite story from our curated collection of quality books. Curating stories that stay with you.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('books.index') }}" 
                   class="bg-[#FF8040] text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-[#ff9663] transition-all transform hover:-translate-y-1 shadow-lg active:scale-95">
                    Browse Books
                </a>
                <a href="{{ route('categories.index') }}" 
                   class="bg-transparent text-[#F5F1DC] border-2 border-[#0046FF] px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-[#0046FF] transition-all shadow-md">
                    View Categories
                </a>
            </div>
        </div>
        
        {{-- Decorative Background Elements --}}
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#0046FF] rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute top-10 right-20 w-32 h-32 bg-[#FF8040] rounded-full opacity-20 blur-2xl"></div>
    </div>

    {{-- Categories Section --}}
    <section class="mb-16">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-black text-[#001BB7] uppercase tracking-tighter">Browse by Category</h2>
                <p class="text-[#0046FF] font-bold mt-1 opacity-70">Explore our diverse genres</p>
            </div>
            <a href="{{ route('categories.index') }}" class="text-[#0046FF] hover:text-[#FF8040] font-black uppercase tracking-widest text-sm flex items-center gap-2 group transition-colors">
                See All 
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="group bg-white p-8 rounded-3xl shadow-sm border border-[#0046FF]/10 hover:border-[#FF8040] hover:shadow-xl transition-all text-center">
                    <div class="w-14 h-14 bg-[#0046FF]/10 text-[#0046FF] rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#FF8040] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="font-black text-[#001BB7] uppercase tracking-tight group-hover:text-[#FF8040] transition-colors">{{ $category->name }}</h3>
                    <p class="text-xs font-bold text-[#0046FF]/60 mt-1 uppercase tracking-widest">{{ $category->books_count ?? 0 }} Books</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured Books Section --}}
    <section class="mb-12">
        <div class="flex flex-col mb-8">
            <h2 class="text-3xl font-black text-[#001BB7] uppercase tracking-tighter">Featured Books</h2>
            <p class="text-[#0046FF] font-bold mt-1 opacity-70">Our latest additions and community favorites</p>
        </div>

        @if($featuredBooks->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($featuredBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        @else
            <div class="bg-[#F5F1DC] border-2 border-dashed border-[#FF8040]/30 rounded-3xl p-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-sm">
                    <svg class="w-8 h-8 text-[#FF8040]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <p class="text-[#001BB7] font-black uppercase tracking-widest">Our library is currently being restocked.</p>
                <p class="text-[#0046FF] text-sm font-bold">Check back soon for new adventures!</p>
            </div>
        @endif
    </section>
@endsection