<button {{ $attributes->merge([
    'type' => 'button', 
    'class' => 'inline-flex items-center px-8 py-3.5 bg-[#F5F1DC]/30 border-2 border-[#0046FF]/10 rounded-2xl font-black text-[10px] text-[#001BB7] uppercase tracking-[0.2em] shadow-sm hover:bg-[#F5F1DC] hover:border-[#0046FF]/30 focus:outline-none focus:ring-2 focus:ring-[#0046FF] focus:ring-offset-2 disabled:opacity-25 transition-all duration-150 active:scale-95'
]) }}>
    {{ $slot }}
</button>