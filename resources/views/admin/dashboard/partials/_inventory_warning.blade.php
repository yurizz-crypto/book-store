@if($books->count() > 0)
<div class="bg-amber-50 border-2 border-amber-400 p-8 rounded-[2.5rem] mb-10 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-amber-600 font-black uppercase tracking-tighter flex items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Inventory Alert: Low Stock
        </h2>
        <span class="text-[10px] font-black bg-amber-200 text-amber-700 px-3 py-1 rounded-full uppercase">
            {{ $books->total() }} Items Total
        </span>
    </div>
    
    <div class="space-y-4 mb-6">
        @foreach($books as $book)
        <div class="group bg-white p-5 rounded-2xl flex items-center justify-between border border-amber-200 shadow-sm transition-all duration-300 hover:border-amber-500 hover:shadow-md">
            <div class="flex items-center gap-4 min-w-0 flex-1">
                <div class="bg-red-600 text-white px-3 py-1 rounded-xl font-black text-xs shrink-0">
                    {{ $book->stock_quantity }} LEFT
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="font-black text-[#001BB7] text-sm truncate uppercase tracking-tight">{{ $book->title }}</span>
                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-widest">Restock Required</span>
                </div>
            </div>

            <div class="flex items-center gap-2 ml-4">
                <a href="{{ route('books.show', $book) }}" class="bg-amber-100 text-amber-600 p-2 rounded-xl hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </a>
                <a href="{{ route('admin.books.edit', $book) }}" class="bg-[#001BB7]/10 text-[#001BB7] p-2 rounded-xl hover:bg-[#001BB7] hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="border-t border-[#F5F1DC] pageturner-pagination">
        <style>
            /* Custom CSS to override default Tailwind Pagination styles */
            .pageturner-pagination nav div:first-child span, 
            .pageturner-pagination nav div:first-child a {
                border-radius: 1rem !important;
                border-color: rgba(0, 70, 255, 0.1) !important;
                color: #001BB7 !important;
                font-weight: 900 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.1em !important;
                font-size: 0.75rem !important;
            }
            
            /* Style active and hover states */
            .pageturner-pagination span[aria-current="page"] span {
                background-color: #001BB7 !important;
                color: #F5F1DC !important;
                border-color: #001BB7 !important;
            }
            
            .pageturner-pagination a:hover {
                color: #FF8040 !important;
                border-color: #FF8040 !important;
                background-color: transparent !important;
            }
        </style>
        {{ $books->appends(['stock_page' => request('stock_page')])->links() }}
    </div>
</div>
@endif