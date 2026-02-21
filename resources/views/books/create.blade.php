@extends('layouts.app')

@section('title', 'Add New Book - PageTurner')

@section('header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-2 text-center">
        <h1 class="text-5xl font-black text-[#001BB7] tracking-tighter uppercase italic">Restocking the Shelves</h1>
        <p class="text-[#0046FF] font-bold mt-2 text-lg opacity-70">Fill in the details to add a new title to your collection.</p>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto pb-24 px-4">
    <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#001BB7]/5 border border-[#0046FF]/5 overflow-hidden">
        {{-- Progress Decorative Bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-[#001BB7] via-[#0046FF] to-[#FF8040]"></div>

        <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <x-input-label for="title" :value="__('Book Title *')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus placeholder="The Great Gatsby" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                {{-- Author --}}
                <div>
                    <x-input-label for="author" :value="__('Author *')" />
                    <x-text-input id="author" name="author" type="text" class="mt-1 block w-full" :value="old('author')" required placeholder="F. Scott Fitzgerald" />
                    <x-input-error :messages="$errors->get('author')" class="mt-2" />
                </div>

                {{-- Category --}}
                <div>
                    <x-input-label for="category_id" :value="__('Genre / Category *')" />
                    <select name="category_id" id="category_id" 
                        class="mt-1 block w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-bold transition-all px-4 py-3">
                        <option value="" class="text-gray-400">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                {{-- ISBN --}}
                <div>
                    <x-input-label for="isbn" :value="__('ISBN *')" />
                    <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full" :value="old('isbn')" required placeholder="978-3-16-148410-0" />
                    <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
                </div>

                {{-- Price & Stock Row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="price" :value="__('Price (₱) *')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" class="mt-1 block w-full" :value="old('price')" required placeholder="599.00" />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="stock_quantity" :value="__('Stock *')" />
                        <x-text-input id="stock_quantity" name="stock_quantity" type="number" class="mt-1 block w-full" :value="old('stock_quantity', 0)" required />
                        <x-input-error :messages="$errors->get('stock_quantity')" class="mt-2" />
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <x-input-label for="description" :value="__('Synopsis / Description')" />
                <textarea name="description" id="description" rows="4" placeholder="Briefly describe the journey this book takes the reader on..."
                    class="mt-1 block w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-medium transition-all px-4 py-3">{{ old('description') }}</textarea>
            </div>

            <div>
                <x-input-label for="cover_image" :value="__('Book Cover Art')" class="mb-4" />
                <x-image-upload name="cover_image" id="cover_image" />
                <x-input-error :messages="$errors->get('cover_image')" class="mt-2 text-center" />
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-6 pt-6 border-t border-[#F5F1DC]">
                <a href="{{ route('books.index') }}" class="text-xs font-black uppercase tracking-widest text-[#001BB7]/40 hover:text-[#FF8040] transition-colors">
                    {{ __('Discard') }}
                </a>
                <x-primary-button class="px-10 py-4 text-xs">
                    {{ __('Publish to Store') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection