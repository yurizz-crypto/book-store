<div id="exportModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('exportModal').classList.add('hidden')"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-[#001BB7]/10">
            <form action="{{ route('admin.export.books') }}" method="GET">
                <div class="bg-white px-8 py-8">
                    <h3 class="text-2xl leading-6 font-black text-[#001BB7] uppercase tracking-tighter mb-6" id="modal-title">
                        Advanced Data Export
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="font-bold text-xs uppercase text-gray-400 tracking-widest border-b pb-2">Filters</h4>
                            
                            <div>
                                <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-1">Stock Status</label>
                                <select name="stock_status" class="w-full text-sm border-gray-300 rounded-xl focus:ring-[#0046FF] focus:border-[#0046FF]">
                                    <option value="">All Books</option>
                                    <option value="in_stock">In Stock Only</option>
                                    <option value="out_of_stock">Out of Stock Only</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-1">Min Price</label>
                                    <input type="number" name="price_min" step="0.01" class="w-full text-sm border-gray-300 rounded-xl focus:ring-[#0046FF] focus:border-[#0046FF]" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-1">Max Price</label>
                                    <input type="number" name="price_max" step="0.01" class="w-full text-sm border-gray-300 rounded-xl focus:ring-[#0046FF] focus:border-[#0046FF]" placeholder="999.99">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-1">Date From</label>
                                    <input type="date" name="date_from" class="w-full text-sm border-gray-300 rounded-xl focus:ring-[#0046FF] focus:border-[#0046FF]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-1">Date To</label>
                                    <input type="date" name="date_to" class="w-full text-sm border-gray-300 rounded-xl focus:ring-[#0046FF] focus:border-[#0046FF]">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="font-bold text-xs uppercase text-gray-400 tracking-widest border-b pb-2">Columns & Format</h4>
                            
                            <div>
                                <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-2">Select Columns</label>
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="id" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>ID</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="isbn" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>ISBN</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="title" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>Title</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="author" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>Author</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="category" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>Category</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="price" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>Price</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="stock" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>Stock</span></label>
                                    <label class="flex items-center space-x-2 cursor-pointer"><input type="checkbox" name="columns[]" value="date" checked class="rounded text-[#0046FF] focus:ring-[#0046FF]"> <span>Date Added</span></label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-[10px] font-black text-[#001BB7] uppercase tracking-widest mb-1">Export Format</label>
                                <select name="format" class="w-full text-sm border-gray-300 rounded-xl focus:ring-[#0046FF] focus:border-[#0046FF] bg-gray-50">
                                    <option value="csv">CSV (.csv)</option>
                                    <option value="xlsx">Excel (.xlsx)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-8 py-4 sm:flex sm:flex-row-reverse rounded-b-[2rem]">
                    <button type="submit" onclick="document.getElementById('exportModal').classList.add('hidden')" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-8 py-3 bg-emerald-500 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto transition-all">
                        Generate File
                    </button>
                    <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-8 py-3 bg-white text-xs font-black uppercase tracking-widest text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0046FF] sm:mt-0 sm:ml-3 sm:w-auto transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>