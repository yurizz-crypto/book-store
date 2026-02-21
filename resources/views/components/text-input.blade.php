@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] placeholder-[#001BB7]/30 focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-bold transition-all px-4 py-3 disabled:opacity-50 disabled:cursor-not-allowed'
]) }}>