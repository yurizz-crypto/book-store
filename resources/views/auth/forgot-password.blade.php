<x-guest-layout>
    {{-- Header Section --}}
    <div class="text-center mb-10">
        {{-- Branding Icon --}}
        <div class="inline-flex items-center justify-center w-16 h-16 bg-[#F5F1DC] rounded-2xl mb-6 shadow-inner">
            <svg class="w-8 h-8 text-[#001BB7]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
        </div>
        
        <h2 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">
            Lost your way?
        </h2>
        <p class="mt-4 text-sm font-bold text-[#0046FF]/60 px-6 leading-relaxed">
            No problem. Enter your email and we'll send a recovery link to help you get back to your books.
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-8" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-8">
        @csrf

        {{-- Email Address --}}
        <div class="group">
            <x-input-label for="email" :value="__('Registered Email')" />
            
            <div class="relative mt-2">
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/30 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                       placeholder="e.g., reader@pageturner.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-[10px] font-black rounded-2xl text-white bg-[#FF8040] hover:bg-[#ff9663] focus:outline-none focus:ring-4 focus:ring-[#FF8040]/20 transition-all shadow-xl shadow-[#FF8040]/20 active:scale-[0.98] uppercase tracking-[0.25em]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-5">
                    <svg class="h-4 w-4 text-white/50 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </span>
                Send Recovery Link
            </button>
        </div>

        {{-- Return to Login --}}
        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40 hover:text-[#0046FF] transition-colors flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>