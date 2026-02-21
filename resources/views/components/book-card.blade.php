@props(['book'])

<div class="bg-white rounded-[2.5rem] shadow-sm border border-[#0046FF]/5 overflow-hidden hover:shadow-2xl hover:shadow-[#001BB7]/10 hover:-translate-y-2 transition-all duration-500 group">
    {{-- Book Cover --}}
    <div class="h-64 bg-[#F5F1DC]/50 flex items-center justify-center overflow-hidden relative">
        @if($book->cover_image)
            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover group-hover:scale-110 transition-transform duration-700">
        @else
            <div class="text-[#0046FF]/20">
                <svg class="h-20 w-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
        
        {{-- Category Badge --}}
        <span class="absolute top-4 left-4 bg-[#001BB7] text-[#F5F1DC] text-[9px] font-black uppercase tracking-[0.2em] px-3 py-1.5 rounded-full shadow-lg">
            {{ $book->category->name }}
        </span>
    </div>

    <div class="p-6">
        <h3 class="font-black text-[#001BB7] truncate text-xl uppercase tracking-tighter">{{ $book->title }}</h3>
        <p class="text-[#0046FF]/60 text-[10px] font-black uppercase tracking-widest mt-1">by {{ $book->author }}</p>

        <div class="flex items-center justify-between mt-5">
            <p class="text-2xl font-black text-[#001BB7] tracking-tighter">
                ₱{{ number_format($book->price, 2) }}
            </p>
            
            {{-- Star Rating --}}
            <div class="flex items-center bg-[#F5F1DC] px-2.5 py-1 rounded-xl">
                @php $rating = $book->reviews->avg('rating') ?? 0; @endphp
                <svg class="h-3.5 w-3.5 text-[#FF8040] fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="ml-1.5 text-[10px] font-black text-[#001BB7]">{{ number_format($rating, 1) }}</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex flex-col gap-2">
            <a href="{{ route('books.show', $book) }}" class="block text-center border-2 border-[#0046FF]/20 text-[#001BB7] py-3 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-[#001BB7] hover:border-[#001BB7] hover:text-[#F5F1DC] transition-all duration-300">
                View Details
            </a>

            @auth
                @if(!Auth::user()->isAdmin())
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full bg-[#FF8040] text-white py-3.5 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-[#FF8040]/20 hover:bg-[#ff9663] hover:shadow-[#FF8040]/40 active:scale-95 transition-all duration-300">
                            Add to Cart
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="block text-center bg-[#FF8040] text-white py-3.5 rounded-2xl font-black uppercase tracking-widest text-[10px] shadow-xl shadow-[#FF8040]/20 hover:bg-[#ff9663] transition-all duration-300">
                    Add to Cart
                </a>
            @endauth
        </div>
    </div>
</div>