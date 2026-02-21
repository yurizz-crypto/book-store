@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2 bg-[#0046FF]/10 border border-[#0046FF]/20 px-4 py-3 rounded-xl shadow-sm transition-all']) }}>
        {{-- Brand Icon --}}
        <svg class="w-4 h-4 text-[#0046FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>

        <span class="font-black text-xs uppercase tracking-widest text-[#001BB7]">
            {{ $status }}
        </span>
    </div>
@endif