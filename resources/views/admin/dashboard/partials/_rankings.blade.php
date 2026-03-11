<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- Top Books Card --}}
    <div class="bg-white p-8 rounded-[3.5rem] border border-[#0046FF]/5 shadow-2xl">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter flex items-center gap-3">
                <div class="p-2 bg-amber-100 rounded-xl">
                    <svg class="w-6 h-6 text-[#FF8040]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                Best Sellers
            </h3>
            <span class="text-[10px] font-black text-[#001BB7]/30 uppercase tracking-widest">Top 3 All-Time</span>
        </div>

        <div class="space-y-4">
            @foreach($topBooks as $index => $book)
            <a href="{{ route('books.show', $book->id) }}" class="group block">
                <div class="flex items-center justify-between p-5 bg-[#F5F1DC]/30 rounded-[2rem] border-2 border-transparent group-hover:border-[#FF8040]/30 group-hover:bg-white group-hover:scale-[1.02] transition-all duration-300 shadow-sm group-hover:shadow-md">
                    <div class="flex items-center gap-5">
                        <span class="font-black text-3xl {{ $index === 0 ? 'text-[#FF8040]' : 'text-[#001BB7]/10' }} transition-colors">0{{ $index + 1 }}</span>
                        <div class="flex flex-col">
                            <span class="font-black text-[#001BB7] text-sm uppercase truncate max-w-[150px]">{{ $book->title }}</span>
                            <span class="text-[9px] font-bold text-[#0046FF]/40 uppercase tracking-widest group-hover:text-[#FF8040] transition-colors">View Details</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block font-black text-[#FF8040] text-sm">₱{{ number_format($book->total_earned, 2) }}</span>
                        <span class="text-[9px] font-black text-[#001BB7]/20 uppercase">Gross Revenue</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Top Categories Card --}}
    <div class="bg-white p-8 rounded-[3.5rem] border border-[#0046FF]/5 shadow-2xl">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-black text-[#001BB7] uppercase tracking-tighter flex items-center gap-3">
                <div class="p-2 bg-blue-50 rounded-xl">
                    <svg class="w-6 h-6 text-[#001BB7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                Trending Genres
            </h3>
            <span class="text-[10px] font-black text-[#001BB7]/30 uppercase tracking-widest">Market Interest</span>
        </div>

        <div class="space-y-4">
            @foreach($topCategories as $index => $cat)
            <a href="{{ route('categories.show', $cat) }}" class="group block">
                <div class="flex items-center justify-between p-5 bg-[#F5F1DC]/30 rounded-[2rem] border-2 border-transparent group-hover:border-[#001BB7]/30 group-hover:bg-white group-hover:scale-[1.02] transition-all duration-300 shadow-sm group-hover:shadow-md">
                    <div class="flex items-center gap-5">
                        <span class="font-black text-3xl text-[#001BB7]/10 group-hover:text-[#001BB7]/30 transition-colors">0{{ $index + 1 }}</span>
                        <span class="font-black text-[#001BB7] text-sm uppercase group-hover:tracking-wider transition-all">{{ $cat->name }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <span class="block font-black text-[#001BB7] text-sm">{{ $cat->sales_count }}</span>
                            <span class="text-[9px] font-black text-[#001BB7]/20 uppercase">Units Sold</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-[#001BB7] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>