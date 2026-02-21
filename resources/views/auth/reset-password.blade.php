<x-guest-layout>
    {{-- Header Section --}}
    <div class="text-center mb-10">
        {{-- Branding Icon: Success/Unlock --}}
        <div class="inline-flex items-center justify-center w-16 h-16 bg-[#F5F1DC] rounded-2xl mb-6 shadow-inner">
            <svg class="w-8 h-8 text-[#0046FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h16.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>
        
        <h2 class="text-4xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">
            New Chapter
        </h2>
        <p class="mt-4 text-sm font-bold text-[#0046FF]/60 px-6 leading-relaxed">
            Set your new secure password to finish your journey back to the collection.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="bg-[#F5F1DC]/20 p-6 rounded-[2rem] border border-[#0046FF]/5">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-2 w-full opacity-60 pointer-events-none" type="email" name="email" :value="old('email', $request->email)" required readonly />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <x-input-label for="password" :value="__('New Password')" />
                <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-[10px] font-black rounded-2xl text-white bg-[#001BB7] hover:bg-[#0046FF] focus:outline-none focus:ring-4 focus:ring-[#0046FF]/20 transition-all shadow-xl shadow-[#001BB7]/20 active:scale-[0.98] uppercase tracking-[0.25em]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-5">
                    <svg class="h-4 w-4 text-white/40 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                Reset and Log In
            </button>
        </div>
    </form>
</x-guest-layout>