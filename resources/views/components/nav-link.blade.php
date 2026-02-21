@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-4 border-[#0046FF] text-[10px] font-black uppercase tracking-[0.2em] leading-5 text-[#001BB7] focus:outline-none focus:border-[#001BB7] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-[10px] font-black uppercase tracking-[0.2em] leading-5 text-[#001BB7]/50 hover:text-[#001BB7] hover:border-[#F5F1DC] focus:outline-none focus:text-[#001BB7] focus:border-[#F5F1DC] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>