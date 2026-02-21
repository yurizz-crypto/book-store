@extends('layouts.app')

@section('title', $category->name . ' - Collection')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        <a href="{{ route('categories.index') }}" class="text-[#0046FF] text-[10px] font-black uppercase tracking-[0.2em] hover:text-[#FF8040] transition flex items-center gap-2 mb-6 group">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to Categories
        </a>

        <h1 class="text-5xl font-black text-[#001BB7] tracking-tighter uppercase">{{ $category->name }}</h1>
        
        <p class="text-[#0046FF]/60 mt-3 text-xl font-bold leading-relaxed max-w-3xl">
            {{ $category->description ?? 'Browsing our curated collection in this genre.' }}
        </p>
        
        <div class="h-1.5 w-24 bg-[#FF8040] mt-8 rounded-full shadow-sm shadow-[#FF8040]/20"></div>
    </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>

        {{-- Pagination styling --}}
        <div class="mt-16 pt-10 border-t border-[#0046FF]/10">
            {{ $books->links() }}
        </div>
    @else
        {{-- Empty State using Cream & Orange --}}
        <div class="text-center py-24 bg-[#F5F1DC]/30 rounded-[3rem] border-2 border-dashed border-[#FF8040]/20">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-6 shadow-sm">
                <svg class="w-8 h-8 text-[#FF8040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-[#001BB7] font-black uppercase tracking-widest">No books found in this collection yet.</p>
            <p class="text-[#0046FF]/50 text-sm font-bold mt-2">Our librarians are currently updating this shelf!</p>
            
            <a href="{{ route('books.index') }}" class="mt-8 inline-block bg-[#001BB7] text-white px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-[#0046FF] transition-all">
                Browse All Books
            </a>
        </div>
    @endif
</div>
@endsection