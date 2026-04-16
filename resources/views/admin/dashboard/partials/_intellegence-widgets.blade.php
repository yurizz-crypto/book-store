<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-white p-6 rounded-2xl border-2 border-[#001BB7]/10 shadow-sm transition hover:shadow-md">
        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Avg Order Value</p>
        <h4 class="text-2xl font-black text-[#001BB7] mt-1">₱{{ number_format($stats['avgOrder'], 2) }}</h4>
        <div class="mt-2 text-[9px] font-bold text-gray-500 uppercase italic">Revenue efficiency per txn</div>
    </div>

    <div class="bg-white p-6 rounded-2xl border-2 border-[#001BB7]/10 shadow-sm transition hover:shadow-md">
        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">24h Sales Growth</p>
        <div class="flex items-baseline gap-2">
            <h4 class="text-2xl font-black {{ $velocity['growth'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1">
                {{ $velocity['growth'] >= 0 ? '+' : '' }}{{ number_format($velocity['growth'], 1) }}%
            </h4>
            <span class="text-[10px] font-bold text-gray-400">vs yesterday</span>
        </div>
        <div class="mt-2 text-[9px] font-bold text-gray-500 uppercase italic">Real-time velocity tracking</div>
    </div>

    <div class="bg-white p-6 rounded-2xl border-2 {{ $lowStock->total() > 0 ? 'border-red-200 bg-red-50' : 'border-[#001BB7]/10' }} shadow-sm">
        <p class="text-[10px] font-black uppercase {{ $lowStock->total() > 0 ? 'text-red-600' : 'text-gray-400' }} tracking-widest">Inventory Health</p>
        <h4 class="text-2xl font-black {{ $lowStock->total() > 0 ? 'text-red-700' : 'text-[#001BB7]' }} mt-1">
            {{ $lowStock->total() }} Low Items
        </h4>
        <div class="mt-2 text-[9px] font-bold {{ $lowStock->total() > 0 ? 'text-red-500' : 'text-gray-500' }} uppercase italic">
            {{ $lowStock->total() > 0 ? 'Immediate restock required' : 'Stock levels optimal' }}
        </div>
    </div>
</div>