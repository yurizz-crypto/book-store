@extends('layouts.app')

@section('title', 'PageTurner - Online Bookstore')

@section('content')
    {{-- Hero Section --}}
    <div class="relative bg-indigo-700 text-white rounded-2xl p-8 mb-12 overflow-hidden shadow-lg">
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-5xl font-extrabold mb-4 tracking-tight">Welcome to PageTurner</h1>
            <p class="text-xl text-indigo-100 mb-8 leading-relaxed">
                Discover your next favorite story from our curated collection of quality books.
            </p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('books.index') }}" class="bg-white text-indigo-700 px-8 py-3 rounded-xl font-bold hover:bg-indigo-50 transition-all transform hover:-translate-y-1 shadow-md">
                    Browse Books
                </a>
                <a href="{{ route('categories.index') }}" class="bg-indigo-600 text-white border border-indigo-500 px-8 py-3 rounded-xl font-bold hover:bg-indigo-500 transition-all shadow-md">
                    View Categories
                </a>
            </div>
        </div>
        
        {{-- Decorative Background Circle --}}
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-600 rounded-full opacity-50 blur-3xl"></div>
    </div>

    {{-- Categories Section --}}
    <section class="mb-16">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Browse by Category</h2>
                <p class="text-gray-500 mt-1">Explore our diverse genres</p>
            </div>
            <a href="{{ route('categories.index') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1 group">
                See All 
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all text-center">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $category->books_count ?? 0 }} Books</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Featured Books Section --}}
    <section class="mb-12">
        <div class="flex flex-col mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Featured Books</h2>
            <p class="text-gray-500 mt-1">Our latest additions and community favorites</p>
        </div>

        @if($featuredBooks->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($featuredBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        @else
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-12 text-center">
                <p class="text-indigo-600 font-medium">Our library is currently being restocked. Check back soon!</p>
            </div>
        @endif
    </section>
@endsection