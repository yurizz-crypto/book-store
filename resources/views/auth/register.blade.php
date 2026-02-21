<x-guest-layout title="Register | PageTurner">
    {{-- Header Section --}}
    <div class="text-center mb-10">
        <h2 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">
            Join the Library
        </h2>
        <p class="text-[#0046FF]/60 font-bold mt-2 text-sm uppercase tracking-widest">
            Create your reader profile
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        {{-- Name Section --}}
        <div class="space-y-5">
            {{-- First Name --}}
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required autofocus 
                    class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                    placeholder="e.g., Jane">
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            {{-- Middle Name --}}
            <div>
                <x-input-label for="middle_name" :value="__('Middle Name (Optional)')" />
                <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name ?? '') }}" 
                    class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                    placeholder="Middle Name">
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            {{-- Last Name --}}
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required 
                    class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                    placeholder="e.g., Austen">
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        {{-- Email Address --}}
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative mt-2">
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                       placeholder="jane@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password Group --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Password --}}
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-2">
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                           placeholder="••••••••">
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm')" />
                <div class="relative mt-2">
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           class="block w-full px-5 py-4 rounded-2xl bg-[#F5F1DC]/30 border-[#0046FF]/10 text-[#001BB7] font-bold placeholder-[#001BB7]/20 shadow-sm focus:ring-4 focus:ring-[#0046FF]/5 focus:border-[#0046FF] transition-all border" 
                           placeholder="••••••••">
                </div>
            </div>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1" />

        {{-- Register Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xs font-black rounded-2xl text-white bg-[#FF8040] hover:bg-[#ff9663] focus:outline-none focus:ring-4 focus:ring-[#FF8040]/20 transition-all shadow-xl shadow-[#FF8040]/20 active:scale-[0.98] uppercase tracking-[0.2em]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-5">
                    <svg class="h-4 w-4 text-white/40 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </span>
                Start Reading
            </button>
        </div>

        {{-- Already registered? --}}
        <p class="mt-4 text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40 text-center">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-[#0046FF] hover:text-[#FF8040] transition-colors border-b-2 border-[#0046FF]/10">
                Sign in here
            </a>
        </p>
    </form>
</x-guest-layout>