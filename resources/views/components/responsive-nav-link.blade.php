@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-4 border-l-4 border-[#FF8040] text-start text-xs font-black uppercase tracking-[0.2em] text-[#001BB7] bg-[#F5F1DC] focus:outline-none focus:text-[#0046FF] focus:bg-[#F5F1DC] transition duration-150 ease-in-out'
            : 'block w-full ps-4 pe-4 py-4 border-l-4 border-transparent text-start text-xs font-black uppercase tracking-[0.2em] text-[#001BB7]/60 hover:text-[#001BB7] hover:bg-[#F5F1DC]/50 hover:border-[#0046FF]/30 focus:outline-none focus:text-[#001BB7] focus:bg-[#F5F1DC]/50 focus:border-[#0046FF]/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>