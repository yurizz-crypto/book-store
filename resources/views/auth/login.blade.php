<x-guest-layout title="Login | PageTurner">
    {{-- Header Section --}}
    <div class="text-center mb-10">
        <h2 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">
            Welcome back
        </h2>
        <p class="text-[#0046FF]/60 font-bold mt-2 text-sm uppercase tracking-widest">
            Enter your library credentials
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email Address --}}
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative mt-2">
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                       placeholder="reader@pageturner.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <x-input-label for="password" :value="__('Password')" />
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black uppercase tracking-widest text-[#0046FF] hover:text-[#FF8040] transition-colors" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-[#0046FF]/20 text-[#001BB7] shadow-sm focus:ring-[#0046FF] transition cursor-pointer" name="remember">
                <span class="ms-3 text-xs font-black uppercase tracking-widest text-[#001BB7]/40 group-hover:text-[#001BB7] transition-colors">
                    Keep me signed in
                </span>
            </label>
        </div>

        {{-- Login Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xs font-black rounded-2xl text-white bg-[#FF8040] hover:bg-[#ff9663] focus:outline-none focus:ring-4 focus:ring-[#FF8040]/20 transition-all shadow-xl shadow-[#FF8040]/20 active:scale-[0.98] uppercase tracking-[0.2em]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-5">
                    <svg class="h-4 w-4 text-white/40 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                Log In
            </button>
        </div>

        <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40 text-center">
            New here? 
            <a href="{{ route('register') }}" class="text-[#0046FF] hover:text-[#FF8040] transition-colors border-b-2 border-[#0046FF]/10">
                Create an account
            </a>
        </p>
    </form>
</x-guest-layout>