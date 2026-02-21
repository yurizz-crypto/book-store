@extends('layouts.app')

@section('title', 'Edit Book - ' . $book->title)

@section('header')
    <div class="max-w-4xl mx-auto pt-12 pb-8 px-4">
        {{-- Navigation Back Link --}}
        <a href="{{ route('books.show', $book) }}" class="text-[#0046FF] text-[10px] font-black uppercase tracking-[0.2em] hover:text-[#FF8040] transition flex items-center gap-2 mb-6 group">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Back to Book Details
        </a>

        <h1 class="text-5xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">Edit Book</h1>
        <p class="text-[#0046FF]/60 mt-3 text-lg font-bold">Updating the record for: <span class="text-[#001BB7]">{{ $book->title }}</span></p>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto pb-24 px-4">
    <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#001BB7]/5 border border-[#0046FF]/5 overflow-hidden">
        {{-- Decorative Gradient Bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-[#001BB7] to-[#0046FF]"></div>

        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <x-input-label for="title" :value="__('Book Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-2 block w-full" 
                        :value="old('title', $book->title)" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                {{-- Author --}}
                <div>
                    <x-input-label for="author" :value="__('Author')" />
                    <x-text-input id="author" name="author" type="text" class="mt-2 block w-full" 
                        :value="old('author', $book->author)" required />
                    <x-input-error :messages="$errors->get('author')" class="mt-2" />
                </div>

                {{-- Category --}}
                <div>
                    <x-input-label for="category_id" :value="__('Category')" />
                    <select name="category_id" id="category_id" 
                        class="mt-2 block w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-bold transition-all px-4 py-3">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                {{-- ISBN --}}
                <div>
                    <x-input-label for="isbn" :value="__('ISBN')" />
                    <x-text-input id="isbn" name="isbn" type="text" class="mt-2 block w-full" 
                        :value="old('isbn', $book->isbn)" required />
                    <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                </div>

                {{-- Price & Stock Row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" :value="__('Price (₱)')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-2 block w-full" 
                            :value="old('price', $book->price)" required />
                    </div>
                    <div>
                        <x-input-label for="stock_quantity" :value="__('Stock')" />
                        <x-text-input id="stock_quantity" name="stock_quantity" type="number" class="mt-2 block w-full" 
                            :value="old('stock_quantity', $book->stock_quantity)" required />
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <x-input-label for="description" :value="__('Description')" />
                <textarea name="description" id="description" rows="5"
                    class="mt-2 block w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-medium transition-all px-4 py-3">{{ old('description', $book->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="cover_image" :value="__('Book Cover Art')" class="mb-4" />
                <x-image-upload name="cover_image" id="cover_image" :value="$book->cover_image" />
                <x-input-error :messages="$errors->get('cover_image')" class="mt-2 text-center" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-6 pt-8 border-t border-[#F5F1DC]">
                <a href="{{ route('books.show', $book) }}" class="text-xs font-black uppercase tracking-widest text-[#001BB7]/40 hover:text-[#FF8040] transition-colors">
                    {{ __('Cancel Changes') }}
                </a>
                <x-primary-button class="px-10 py-4">
                    {{ __('Update') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection