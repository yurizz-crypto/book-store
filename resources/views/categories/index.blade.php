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
            <div class="group relative bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-200">
                {{-- Make the entire row a link --}}
                
                <div class="relative z-10 p-6 flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        {{-- Icon/Avatar Placeholder --}}
                        <a href="{{ route('categories.show', $category) }}">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold text-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                        </a>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-gray-500 text-sm mt-1 max-w-2xl truncate">
                                {{ $category->description ?? 'No description provided.' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-8">
                        {{-- Stats --}}
                        <div class="text-center hidden sm:block">
                            <span class="block text-2xl font-black text-gray-900">{{ $category->books_count }}</span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Books</span>
                        </div>

                        {{-- Admin Actions --}}
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="flex items-center gap-2 relative z-20">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="p-2.5 text-amber-500 hover:bg-amber-50 rounded-xl transition-colors" title="Edit Category">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-colors" title="Delete Category">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth

                        {{-- Chevron --}}
                        <div class="text-gray-300 group-hover:text-indigo-500 transition-colors">
                            <a href="{{ route('categories.show', $category) }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
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