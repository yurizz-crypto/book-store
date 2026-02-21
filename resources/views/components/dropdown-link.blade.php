<a {{ $attributes->merge([
    'class' => 'block w-full px-6 py-3 text-start text-xs font-black uppercase tracking-widest text-[#001BB7] hover:bg-[#F5F1DC] hover:text-[#0046FF] border-l-4 border-transparent hover:border-[#FF8040] focus:outline-none focus:bg-[#F5F1DC] transition duration-200 ease-in-out'
]) }}>
    {{ $slot }}
</a>