@extends('layouts.app')

@section('title', $book->title . ' - PageTurner')

@section('content')
    <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#001BB7]/5 overflow-hidden border border-[#0046FF]/5">
        <div class="md:flex">
            {{-- Book Cover with Brand Backdrop --}}
            <div class="md:w-1/3 bg-[#F5F1DC]/30 p-12 flex items-center justify-center border-r border-[#F5F1DC]">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" 
                         class="max-h-[500px] object-contain shadow-[20px_20px_60px_rgba(0,27,183,0.15)] rounded-r-lg -rotate-2 hover:rotate-0 transition-all duration-700">
                @else
                    <div class="text-[#0046FF]/20">
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
                        <span class="bg-[#001BB7] text-[#F5F1DC] text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full shadow-lg">{{ $book->category->name }}</span>
                        <h1 class="text-5xl font-black text-[#001BB7] mt-6 leading-none tracking-tighter uppercase">{{ $book->title }}</h1>
                        <p class="text-xl text-[#0046FF]/60 font-bold mt-2 italic">by <span class="text-[#001BB7] not-italic">{{ $book->author }}</span></p>
                    </div>
                    
                    @if(Auth::check() && Auth::user()->isAdmin())
                        <x-admin-actions
                            label="Book"
                            :editRoute="route('admin.books.edit', $book)" 
                            :deleteRoute="route('admin.books.destroy', $book)" 
                        ></x-admin-actions>
                    @endif
                </div>

                {{-- Rating Summary --}}
                <div class="flex items-center mt-8 bg-[#F5F1DC] w-max px-5 py-2.5 rounded-2xl shadow-inner">
                    @php $avgRating = $book->reviews->avg('rating') ?? 0; @endphp
                    <div class="flex text-[#FF8040]">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-5 w-5 {{ $i <= round($avgRating) ? 'fill-current' : 'text-[#001BB7]/10' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="ml-3 text-sm font-black text-[#001BB7]">{{ number_format($avgRating, 1) }}</span>
                    <span class="mx-3 text-[#001BB7]/20">|</span>
                    <span class="text-[10px] text-[#001BB7]/50 font-black uppercase tracking-widest">{{ $book->reviews->count() }} reviews</span>
                </div>

                {{-- Pricing --}}
                <div class="mt-10 flex items-baseline gap-4">
                    <p class="text-6xl font-black text-[#001BB7] tracking-tighter">₱ {{ number_format($book->price, 2) }}</p>
                    <span class="text-xs font-black uppercase tracking-widest {{ $book->stock_quantity > 0 ? 'text-emerald-600' : 'text-[#FF8040]' }}">
                        {{ $book->stock_quantity > 0 ? '● In Stock' : '● Out of Stock' }}
                    </span>
                </div>

                {{-- Order Form --}}
                @auth
                    @if(!Auth::user()->isAdmin())
                    <div x-data="{ quantity: 1 }" class="mt-12 p-8 bg-[#F5F1DC]/40 rounded-[2.5rem] border border-[#0046FF]/5 shadow-inner">
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <div class="flex items-center bg-white rounded-2xl border border-[#0046FF]/10 p-2 shadow-sm">
                                    <button type="button" @click="if(quantity > 1) quantity--" class="w-12 h-12 flex items-center justify-center hover:bg-[#F5F1DC] rounded-xl transition text-[#001BB7] font-black text-xl">-</button>
                                    <input type="number" name="quantity" x-model="quantity" readonly class="w-16 text-center border-none focus:ring-0 font-black text-xl text-[#001BB7] bg-transparent">
                                    <button type="button" @click="quantity++" class="w-12 h-12 flex items-center justify-center hover:bg-[#F5F1DC] rounded-xl transition text-[#001BB7] font-black text-xl">+</button>
                                </div>
                                <button type="submit" class="flex-1 w-full bg-[#FF8040] text-white py-5 rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs hover:bg-[#ff9663] transition-all shadow-xl shadow-[#FF8040]/30 active:scale-95">
                                    Add to Collection
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                @else
                    <div class="mt-12">
                        <a href="{{ route('login') }}" class="block text-center bg-[#001BB7] text-[#F5F1DC] py-5 rounded-[2rem] font-black uppercase tracking-[0.2em] text-xs hover:bg-[#0046FF] transition-all shadow-xl shadow-[#001BB7]/20">Sign in to Purchase</a>
                    </div>
                @endauth

                <div class="mt-14 space-y-8">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#001BB7]/40 mb-3">Synopsis</h3>
                        <p class="text-[#001BB7]/80 leading-relaxed text-xl font-medium">{{ $book->description }}</p>
                    </div>
                    <div class="flex flex-wrap gap-10 border-t border-[#F5F1DC] pt-8">
                        <div>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-[#001BB7]/40 mb-1">ISBN Reference</span>
                            <span class="font-mono font-bold text-[#001BB7] text-lg">{{ $book->isbn }}</span>
                        </div>
                        <div>
                            <span class="block text-[9px] font-black uppercase tracking-widest text-[#001BB7]/40 mb-1">Availability</span>
                            <span class="font-black text-[#001BB7] text-lg">{{ $book->stock_quantity }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Community Reviews Section --}}
    <div class="mt-24">
        <div class="flex items-center gap-6 mb-12">
            <h2 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">Reviews</h2>
            <div class="h-1 flex-grow bg-[#F5F1DC] rounded-full"></div>
        </div>

        @auth
            @php
                $bookID = $book->id;
                $hasCompletedOrder = Auth::user()->orders()
                    ->where('status', 'completed')
                    ->whereHas('orderItems', fn($q) => $q->where('book_id', $bookID))
                    ->exists();
                $existingReview = $book->reviews->where('user_id', auth()->id())->first();
            @endphp

            @if ($hasCompletedOrder)
                <div x-data="{ isEditing: {{ $existingReview ? 'false' : 'true' }} }" class="mb-16">
                    @if($existingReview)
                        <div x-show="!isEditing" class="bg-[#F5F1DC]/30 rounded-[2.5rem] p-10 flex flex-col md:flex-row justify-between items-center gap-8 border border-[#0046FF]/5">
                            <div class="flex-1">
                                <h3 class="font-black text-[#0046FF] uppercase text-[10px] tracking-widest mb-4">Your Contribution</h3>
                                <div class="flex text-[#FF8040] mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-5 w-5 {{ $i <= $existingReview->rating ? 'fill-current' : 'text-[#001BB7]/10' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-[#001BB7] font-bold text-xl italic leading-relaxed">"{{ $existingReview->comment }}"</p>
                            </div>
                            <button @click="isEditing = true" class="bg-white text-[#001BB7] px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-[#001BB7]/5 hover:bg-[#001BB7] hover:text-white transition-all">Edit Review</button>
                        </div>
                    @endif

                    <div x-show="isEditing" class="bg-white rounded-[3rem] shadow-xl border border-[#0046FF]/10 p-10">
                        <form action="{{ route('reviews.store', $book) }}" method="POST" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#001BB7] mb-3 ml-1">Rating</label>
                                    <select name="rating" class="w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 rounded-2xl font-black text-[#001BB7] focus:ring-[#0046FF] focus:border-[#0046FF]" required>
                                        @for($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ $existingReview && $existingReview->rating == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-[#001BB7] mb-3 ml-1">Your Narrative</label>
                                    <textarea name="comment" rows="3" class="w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 rounded-2xl font-medium text-[#001BB7] placeholder-[#001BB7]/20 focus:ring-[#0046FF] focus:border-[#0046FF]" placeholder="Share your literary journey...">{{ $existingReview->comment ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="flex justify-end gap-4">
                                @if($existingReview) <button type="button" @click="isEditing = false" class="text-xs font-black uppercase tracking-widest text-[#001BB7]/40 mr-4">Discard</button> @endif
                                <x-primary-button>{{ $existingReview ? 'Update Perspective' : 'Publish Review' }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                @if(!Auth::user()->isAdmin())
                    <div class="bg-[#F5F1DC]/30 border-2 border-dashed border-[#001BB7]/10 rounded-[3rem] p-10 mb-16 text-center">
                        <p class="text-[#001BB7]/60 font-bold">Exclusive to verified readers. <span class="text-[#001BB7] font-black">Complete your purchase</span> to join the conversation.</p>
                    </div>
                @endif
            @endif
        @endauth

        {{-- Community Feed --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($book->reviews as $review)
                <div class="bg-white rounded-[2.5rem] border border-[#0046FF]/5 p-10 hover:shadow-2xl hover:shadow-[#001BB7]/5 transition-all group">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-5">
                            <div class="h-12 w-12 rounded-2xl bg-[#0046FF] text-[#F5F1DC] flex items-center justify-center font-black text-xl shadow-lg shadow-[#0046FF]/20">
                                {{ substr($review->user->first_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-black text-[#001BB7] uppercase tracking-tighter text-lg leading-none">{{ $review->user->getFullName() }}</p>
                                <span class="text-[9px] text-[#0046FF]/40 font-black uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if(Auth::check() && (Auth::id() === $review->user_id || Auth::user()->isAdmin()))
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[#001BB7]/10 hover:text-[#FF8040] transition-colors" onclick="return confirm('Remove this perspective?')">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="flex text-[#FF8040] mt-6 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-4 w-4 {{ $i <= $review->rating ? 'fill-current' : 'text-[#001BB7]/10' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-[#001BB7]/80 font-bold italic text-lg leading-relaxed">"{{ $review->comment }}"</p>
                </div>
            @empty
                <div class="md:col-span-2 py-24 text-center text-[#001BB7]/20 font-black uppercase tracking-[0.3em] border-2 border-dashed border-[#F5F1DC] rounded-[3rem]">Waiting for the first reader.</div>
            @endforelse
        </div>
    </div>
@endsection