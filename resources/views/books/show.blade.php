@extends('layouts.app')

@section('title', $book->title . ' - PageTurner')

@section('content')
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            {{-- Book Cover --}}
            <div class="md:w-1/3 bg-gray-200 p-8 flex items-center justify-center">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-h-96 object-contain">
                @else
                    <svg class="h-48 w-48 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                @endif
            </div>

            {{-- Book Details --}}
            <div class="md:w-2/3 p-8">
                <span class="text-indigo-600 text-sm font-medium">{{ $book->category->name }}</span>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ $book->title }}</h1>
                <p class="text-xl text-gray-600 mt-1">by {{ $book->author }}</p>

                {{-- Rating Summary --}}
                <div class="flex items-center mt-4">
                    @php $avgRating = $book->reviews->avg('rating') ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-6 w-6 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                    <span class="ml-2 text-gray-600">{{ number_format($avgRating, 1) }} ({{ $book->reviews->count() }} reviews)</span>
                </div>

                <p class="text-3xl font-bold text-indigo-600 mt-4">₱{{ number_format($book->price, 2) }}</p>

                <div class="mt-4">
                    <span class="text-sm font-medium {{ $book->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                        @if($book->stock_quantity > 0)
                            In Stock ({{ $book->stock_quantity }} available)
                        @else
                            Out of Stock
                        @endif
                    </span>
                </div>

                <div class="mt-4">
                    <p class="text-gray-600 text-sm"><strong>ISBN:</strong> {{ $book->isbn }}</p>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold text-gray-800 border-b pb-2">Description</h3>
                    <p class="text-gray-600 mt-2 leading-relaxed">{{ $book->description }}</p>
                </div>

                {{-- Admin Actions --}}
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="mt-8 inline-flex items-center gap-2 bg-base-200/50 p-2 rounded-2xl border border-base-300 backdrop-blur-sm">
                            <div class="px-3 mr-1">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-50">Admin Tools</span>
                            </div>

                            <div class="tooltip" data-tip="Edit Book">
                                <a href="{{ route('admin.books.edit', $book) }}" 
                                class="btn btn-circle btn-ghost btn-sm hover:bg-warning hover:text-warning-content transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                            </div>

                            <div class="tooltip tooltip-error" data-tip="Delete Book">
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-circle btn-ghost btn-sm hover:bg-error hover:text-error-content transition-all duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    
    {{-- Reviews Section --}}
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>

        @auth
            @if (!auth()->user()->isAdmin())
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="font-semibold text-lg mb-4">Write a Review</h3>
                    <form action="{{ route('reviews.store', $book) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2 font-medium">Rating</label>
                            <select name="rating" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">Select rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2 font-medium">Comment</label>
                            <textarea name="comment" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Share your thoughts..."></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">Submit Review</button>
                    </form>
                </div>
            @endif
        @endauth
        <x-alert type="info" class="mb-8">
            Please <a href="{{ route('login') }}" class="text-indigo-600 font-bold hover:underline">Login</a> to write a review.
        </x-alert>

        {{-- Display Reviews --}}
        <div class="space-y-4">
            @forelse($book->reviews as $review)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->user->name }}</p>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-500 text-xs">{{ $review->created_at->diffForHumans() }}</span>
                            @auth
                                @if(auth()->id() === $review->user_id || auth()->user()->role === 'admin')
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition" onclick="return confirm('Delete this review?')">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-600 mt-3 italic">"{{ $review->comment }}"</p>
                    @endif
                </div>
            @empty
                @if (!auth()->user()->isAdmin())
                    <x-alert type="info">No reviews yet. Be the first to share your thoughts!</x-alert>
                @else
                    <x-alert type="info">No reviews yet.</x-alert>
                @endif
            @endforelse
        </div>
    </div>
@endsection