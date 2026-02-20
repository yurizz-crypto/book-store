@extends('layouts.app')

@section('title', $book->title . ' - PageTurner')

@section('content')
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <div class="md:flex">
            {{-- Book Cover --}}
            <div class="md:w-1/3 bg-gray-50 p-12 flex items-center justify-center border-r border-gray-50">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-h-[500px] object-contain shadow-2xl rounded-lg rotate-2 hover:rotate-0 transition-transform duration-500">
                @else
                    <div class="text-gray-300">
                        <svg class="h-48 w-48" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Book Details --}}
            <div class="md:w-2/3 p-10 lg:p-16">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="bg-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest px-3 py-1 rounded-full">{{ $book->category->name }}</span>
                        <h1 class="text-4xl font-black text-gray-900 mt-4 leading-tight">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-500 font-medium mt-1">by <span class="text-gray-800">{{ $book->author }}</span></p>
                    </div>
                    
                    {{-- Admin Actions Component --}}
                    <x-admin-actions
                        label="Book"
                        :editRoute="route('admin.books.edit', $book)" 
                        :deleteRoute="route('admin.books.destroy', $book)" 
                    ></x-admin-actions>
                </div>

                {{-- Rating Summary --}}
                <div class="flex items-center mt-6 bg-gray-50 w-max px-4 py-2 rounded-2xl">
                    @php $avgRating = $book->reviews->avg('rating') ?? 0; @endphp
                    <div class="flex text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-5 w-5 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-3 text-sm font-bold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                    <span class="mx-2 text-gray-300">|</span>
                    <span class="text-sm text-gray-500 font-medium">{{ $book->reviews->count() }} reviews</span>
                </div>

                <div class="mt-8 flex items-baseline gap-4">
                    <p class="text-5xl font-black text-indigo-600 tracking-tighter">₱ {{ number_format($book->price, 2) }}</p>
                    <span class="text-sm font-bold {{ $book->stock_quantity > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ $book->stock_quantity > 0 ? '● In Stock' : '● Out of Stock' }}
                    </span>
                </div>

                {{-- Ordering System --}}
                @auth
                    @if(auth()->user()->role === 'customer')
                    <div x-data="{ quantity: 1, added: false }" class="mt-10 p-6 bg-indigo-50 rounded-[2rem] border border-indigo-100">
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center bg-white rounded-xl border border-indigo-200 p-1">
                                    <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-10 flex items-center justify-center hover:bg-indigo-50 rounded-lg transition text-indigo-600 font-bold">-</button>
                                    <input type="number" name="quantity" x-model="quantity" readonly class="w-12 text-center border-none focus:ring-0 font-bold text-indigo-600 bg-transparent">
                                    <button type="button" @click="quantity++" class="w-10 h-10 flex items-center justify-center hover:bg-indigo-50 rounded-lg transition text-indigo-600 font-bold">+</button>
                                </div>
                                <button type="submit" class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 active:scale-95">
                                    Order Now
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                @else
                    <div class="mt-10">
                        <a href="{{ route('login') }}" class="block text-center bg-gray-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-black transition">Login to Place Order</a>
                    </div>
                @endauth

                <div class="mt-12 space-y-6">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Description</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">{{ $book->description }}</p>
                    </div>
                    <div class="flex gap-10 border-t border-gray-100 pt-6">
                        <div>
                            <span class="block text-[10px] font-black uppercase text-gray-400">ISBN Number</span>
                            <span class="font-mono font-bold text-gray-700">{{ $book->isbn }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-black uppercase text-gray-400">Availability</span>
                            <span class="font-bold text-gray-700">{{ $book->stock_quantity }} Units</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Reviews Section --}}
    <div class="mt-20">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Community Reviews</h2>
        </div>

        @auth
            @php
                $currentBookId = $book->id;
                $hasCompletedOrder = auth()->user()->orders()
                    ->where('status', 'completed')
                    ->whereHas('orderItems', fn($q) => $q->where('book_id', $currentBookId))
                    ->exists();
                $existingReview = $book->reviews->where('user_id', auth()->id())->first();
            @endphp

            @if ($hasCompletedOrder)
                <div x-data="{ isEditing: {{ $existingReview ? 'false' : 'true' }} }" class="mb-12">
                    {{-- User's current review summary --}}
                    @if($existingReview)
                        <div x-show="!isEditing" class="bg-indigo-50 rounded-3xl p-8 flex flex-col md:flex-row justify-between items-center gap-6 border border-indigo-100">
                            <div>
                                <h3 class="font-black text-indigo-600 uppercase text-xs tracking-widest mb-2">Your Review</h3>
                                <div class="flex text-yellow-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $existingReview->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-gray-700 font-medium italic">"{{ $existingReview->comment }}"</p>
                            </div>
                            <button @click="isEditing = true" class="bg-white text-indigo-600 px-6 py-3 rounded-2xl font-black uppercase tracking-widest text-xs shadow-sm hover:shadow-md transition">
                                Edit Review
                            </button>
                        </div>
                    @endif

                    {{-- Review Form --}}
                    <div x-show="isEditing" x-transition class="bg-white rounded-3xl shadow-sm border border-indigo-100 p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-black text-indigo-600 uppercase text-xs tracking-widest">
                                {{ $existingReview ? 'Update Your Review' : 'Write a Review' }}
                            </h3>
                            @if($existingReview)
                                <button @click="isEditing = false" class="text-gray-400 hover:text-gray-600 text-xs font-bold uppercase">Cancel</button>
                            @endif
                        </div>
                        <form action="{{ route('reviews.store', $book) }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-1">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Rating</label>
                                    <select name="rating" class="w-full border-gray-200 rounded-xl font-bold" required>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ $existingReview && $existingReview->rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Thoughts</label>
                                    <textarea name="comment" rows="2" class="w-full border-gray-200 rounded-xl" placeholder="I loved this book because...">{{ $existingReview->comment ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <x-primary-button>{{ $existingReview ? 'Save Changes' : 'Submit Review' }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                @if(auth()->user()->role !== 'admin')
                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded-3xl p-8 mb-12 text-center">
                        <p class="text-gray-500 font-bold text-sm">Only customers who have a <span class="text-indigo-600 font-black">completed order</span> for this book can leave reviews.</p>
                    </div>
                @endif
            @endif
        @else
            <div class="bg-indigo-600 rounded-3xl p-8 mb-12 flex items-center justify-between">
                <p class="text-white font-bold italic text-lg">Sign in to join the conversation.</p>
                <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-xs">Login</a>
            </div>
        @endauth

        {{-- Community Feed --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($book->reviews as $review)
                <div class="bg-white rounded-3xl border border-gray-100 p-8 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-black">
                                {{ substr($review->user->getFullName(), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-gray-900 leading-none">{{ $review->user->name }}</p>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        @auth
                            @if(auth()->id() === $review->user_id || auth()->user()->role === 'admin')
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors" onclick="return confirm('Delete this review?')">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    
                    <div class="flex text-yellow-400 mt-4 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>

                    @if($review->comment)
                        <p class="text-gray-600 font-medium italic leading-relaxed">"{{ $review->comment }}"</p>
                    @endif
                </div>
            @empty
                <div class="md:col-span-2 py-16 text-center text-gray-400 font-black uppercase tracking-widest border-2 border-dashed border-gray-100 rounded-[3rem]">
                    No reviews yet.
                </div>
            @endforelse
        </div>
    </div>
@endsection