<x-guest-layout title="Secure Login | PageTurner">
    {{-- Header Section --}}
    <div class="text-center mb-10">
        <h2 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">
            Verify
        </h2>
        <p class="text-[#0046FF]/60 font-bold mt-2 text-sm uppercase tracking-widest px-4">
            {{ __('Check your email for a 6-digit code') }}
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-6">
        @csrf

        {{-- Verification Code Input --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <x-input-label for="code" :value="__('Security Code')" />
                <span class="text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40">
                    {{ __('Expires in 10 mins') }}
                </span>
            </div>
            
            <div class="relative">
                <input id="code" 
                       type="text" 
                       name="code" 
                       required 
                       autofocus 
                       placeholder="000000"
                       class="block w-full px-5 py-4 text-center text-2xl tracking-[0.5em] rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-black placeholder-[#001BB7]/10 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border"
                       autocomplete="one-time-code">
            </div>
            <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
        </div>

        {{-- Help Text --}}
        <div class="p-4 rounded-2xl bg-[#0046FF]/5 border border-[#0046FF]/10">
            <p class="text-[10px] font-bold uppercase tracking-wider text-[#001BB7]/60 leading-relaxed text-center">
                {{ __('For your security, we need to confirm it\'s really you. If you didn\'t receive the code, please check your spam folder.') }}
            </p>
        </div>

        {{-- Verify Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xs font-black rounded-2xl text-white bg-[#FF8040] hover:bg-[#ff9663] focus:outline-none focus:ring-4 focus:ring-[#FF8040]/20 transition-all shadow-xl shadow-[#FF8040]/20 active:scale-[0.98] uppercase tracking-[0.2em]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-5">
                    <svg class="h-4 w-4 text-white/40 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </span>
                Verify Code
            </button>
        </div>

        {{-- Back to Login --}}
        <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40 text-center">
            {{ __('Wrong email?') }} 
            <a href="{{ route('login') }}" class="text-[#0046FF] hover:text-[#FF8040] transition-colors border-b-2 border-[#0046FF]/10">
                {{ __('Back to Login') }}
            </a>
        </p>
    </form>
</x-guest-layout>