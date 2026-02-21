<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center px-8 py-4 bg-[#001BB7] border border-transparent rounded-2xl font-black text-[10px] text-[#F5F1DC] uppercase tracking-[0.2em] hover:bg-[#0046FF] active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#0046FF] focus:ring-offset-2 transition-all duration-150 shadow-xl shadow-[#001BB7]/10'
]) }}>
    {{ $slot }}
</button>