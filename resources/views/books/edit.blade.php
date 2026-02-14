@extends('layouts.app')

@section('title', 'Edit Book - ' . $book->title)

@section('header')
    <div class="max-w-2xl mx-auto pt-12 pb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Book</h1>
        <p class="text-gray-500 mt-2">Modify the details for <span class="text-indigo-600 font-semibold">{{ $book->title }}</span></p>
    </div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto pb-20">
    {{-- Main Container - Similar to Login/Register --}}
    <div class="bg-white shadow sm:rounded-xl p-8 border border-gray-100">
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div>
                <x-input-label for="title" :value="__('Book Title')" class="font-semibold" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" 
                    :value="old('title', $book->title)" required autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            {{-- Author --}}
            <div>
                <x-input-label for="author" :value="__('Author')" class="font-semibold" />
                <x-text-input id="author" name="author" type="text" class="mt-1 block w-full" 
                    :value="old('author', $book->author)" required />
                <x-input-error :messages="$errors->get('author')" class="mt-2" />
            </div>

            {{-- Category --}}
            <div>
                <x-input-label for="category_id" :value="__('Category')" class="font-semibold" />
                <select name="category_id" id="category_id" 
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- ISBN --}}
                <div>
                    <x-input-label for="isbn" :value="__('ISBN')" class="font-semibold" />
                    <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" 
                        :value="old('isbn', $book->isbn)" required />
                    <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                </div>
                {{-- Price --}}
                <div>
                    <x-input-label for="price" :value="__('Price (₱)')" class="font-semibold" />
                    <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" 
                        :value="old('price', $book->price)" required />
                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                </div>
            </div>

            {{-- Stock --}}
            <div>
                <x-input-label for="stock_quantity" :value="__('Stock Quantity')" class="font-semibold" />
                <x-text-input id="stock_quantity" name="stock_quantity" type="number" class="mt-1 block w-full" 
                    :value="old('stock_quantity', $book->stock_quantity)" required />
                <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
            </div>

            {{-- Description --}}
            <div>
                <x-input-label for="description" :value="__('Description')" class="font-semibold" />
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $book->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            {{-- Current Image Preview & Upload --}}
            <div class="pt-4 border-t border-gray-100">
                <x-input-label :value="__('Cover Image')" class="font-semibold mb-4" />
                <div class="flex items-center gap-6">
                    @if($book->cover_image)
                        <div class="shrink-0">
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Current cover" class="h-24 w-16 object-cover rounded-lg shadow-sm">
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="cover_image" id="cover_image" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="mt-1 text-xs text-gray-400 italic">Leave empty to keep the current image.</p>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('cover_image')" class="mt-2" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <a href="{{ route('books.show', $book) }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition">
                    {{ __('Cancel') }}
                </a>
                <x-primary-button class="px-8 py-3">
                    {{ __('Update Book Information') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection