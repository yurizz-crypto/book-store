<x-guest-layout>
    <div class="mb-8 text-center">
        {{-- Security Icon --}}
        <div class="inline-flex items-center justify-center w-16 h-16 bg-[#F5F1DC] rounded-2xl mb-6 shadow-inner">
            <svg class="w-8 h-8 text-[#001BB7]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        <h2 class="text-2xl font-black text-[#001BB7] uppercase tracking-tighter mb-2">Secure Area</h2>
        <p class="text-sm font-bold text-[#0046FF]/60 px-4">
            {{ __('Please confirm your password to verify your identity before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <div class="bg-[#F5F1DC]/20 p-6 rounded-[2rem] border border-[#0046FF]/5">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" 
                            class="block mt-2 w-full"
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-col gap-4">
            <x-primary-button class="w-full justify-center py-4">
                {{ __('Unlock Access') }}
            </x-primary-button>
            
            <a href="{{ url()->previous() }}" class="text-center text-[10px] font-black uppercase tracking-widest text-[#001BB7]/40 hover:text-[#FF8040] transition-colors">
                Nevermind, take me back
            </a>
        </div>
    </form>
</x-guest-layout>