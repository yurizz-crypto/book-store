<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-[#001BB7] p-10 rounded-[3.5rem] shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#FF8040] rounded-full blur-[80px] opacity-20"></div>
        <div class="relative z-10">
            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-white/60">Gross Revenue</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="text-[#FF8040] text-2xl font-black">₱</span>
                <h3 class="text-6xl font-black text-white tracking-tighter">{{ number_format($revenue, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="bg-white p-10 rounded-[3.5rem] border border-[#0046FF]/5 shadow-xl flex flex-col justify-center">
        <span class="text-[11px] font-black uppercase tracking-[0.3em] text-[#001BB7]/40">Average Order Value</span>
        <div class="flex items-baseline gap-2 mt-2">
            <span class="text-[#001BB7] text-xl font-black">₱</span>
            <h3 class="text-5xl font-black text-[#001BB7] tracking-tighter">{{ number_format($avgOrder, 2) }}</h3>
        </div>
    </div>
</div>