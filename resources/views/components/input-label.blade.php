@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block font-black text-[10px] uppercase tracking-[0.2em] text-[#001BB7] mb-2 ml-1 opacity-70 group-focus-within:opacity-100 transition-opacity'
]) }}>
    {{ $value ?? $slot }}
</label>