<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'inline-flex items-center px-6 py-3 bg-[#FF8040] border border-transparent rounded-2xl font-black text-[10px] text-white uppercase tracking-[0.2em] shadow-lg shadow-[#FF8040]/20 hover:bg-[#ff9663] hover:shadow-[#FF8040]/40 active:scale-95 focus:outline-none focus:ring-2 focus:ring-[#FF8040] focus:ring-offset-2 transition-all duration-150'
]) }}>
    {{ $slot }}
</button>