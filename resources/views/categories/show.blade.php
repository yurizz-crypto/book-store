@extends('layouts.app')

@section('title', $category->name . ' - Collection')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6">
        <a href="{{ route('categories.index') }}" class="text-indigo-600 text-sm font-bold uppercase tracking-widest hover:text-indigo-700 transition flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            All Categories
        </a>
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $category->name }}</h1>
        <p class="text-gray-500 mt-2 text-lg">{{ $category->description ?? 'Browsing books in this genre.' }}</p>
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
        <div class="mt-16">
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center py-24 bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200">
            <p class="text-gray-400 font-medium">No books found in this category yet.</p>
        </div>
    @endif
</div>
@endsection