@extends('layouts.app')

@section('title', 'Categories - PageTurner')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-6 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Categories</h1>
            <p class="text-gray-500 mt-2 text-lg">Manage your book genres and classifications.</p>
        </div>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-indigo-100 active:scale-95">
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
            <div class="group relative bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-200 active:scale-[0.99]">
                {{-- Entire Row Clickable --}}
                <a href="{{ route('categories.show', $category) }}" class="absolute inset-0 z-10 w-full h-full"></a>
                
                <div class="relative z-20 p-6 flex items-center justify-between pointer-events-none">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $category->name }}</h3>
                            <p class="text-gray-500 text-sm mt-1 max-w-2xl truncate">{{ $category->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-8 pointer-events-auto">
                        <div class="text-center hidden sm:block">
                            <span class="block text-2xl font-black text-gray-900">{{ $category->books_count }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Books</span>
                        </div>

                        <x-admin-actions 
                            label="Category"
                            :editRoute="route('admin.categories.edit', $category)" 
                            :deleteRoute="route('admin.categories.destroy', $category)" 
                        />

                        <div class="text-gray-300 group-hover:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $categories->links() }}
    </div>
</div>
@endsection