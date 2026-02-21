@props(['type' => 'info'])

@php
    $classes = [
        'success' => 'bg-[#0046FF]/10 border-[#0046FF]/20 text-[#001BB7]',
        'info'    => 'bg-[#0046FF]/5 border-[#0046FF]/10 text-[#001BB7]',
        
        'error'   => 'bg-[#FF8040]/10 border-[#FF8040]/30 text-[#001BB7]',
        'warning' => 'bg-[#FF8040]/5 border-[#FF8040]/20 text-[#001BB7]',
    ];

    $icons = [
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'error'   => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'info'    => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ];

    $selectedClass = $classes[$type] ?? $classes['info'];
    $iconPath = $icons[$type] ?? $icons['info'];
@endphp

<div {{ $attributes->merge(['class' => "border-l-4 px-6 py-4 rounded-2xl shadow-sm flex items-center gap-4 transition-all $selectedClass"]) }} role="alert">
    {{-- Dynamic Icon based on Type --}}
    <svg class="w-6 h-6 flex-shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
    </svg>
    
    <div class="text-sm font-bold tracking-tight">
        {{ $slot }}
    </div>
</div>