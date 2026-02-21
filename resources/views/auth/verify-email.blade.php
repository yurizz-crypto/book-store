<x-guest-layout>
    {{-- Branding & Illustration Header --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-[#F5F1DC] rounded-[2rem] mb-6 shadow-inner relative">
            {{-- Envelope Icon with a "notification" dot in Sunset Orange --}}
            <svg class="w-10 h-10 text-[#001BB7]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
            <span class="absolute top-4 right-4 w-4 h-4 bg-[#FF8040] border-4 border-white rounded-full animate-pulse"></span>
        </div>

        <h2 class="text-3xl font-black text-[#001BB7] tracking-tighter uppercase leading-none">Check your mail</h2>
        <p class="mt-4 text-sm font-bold text-[#0046FF]/60 px-2 leading-relaxed">
            {{ __('Thanks for joining PageTurner! We\'ve sent a verification link to your inbox. Just click it to unlock your full reader benefits.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-8 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-xs font-black uppercase tracking-widest text-emerald-700">
                {{ __('A fresh link has been dispatched.') }}
            </p>
        </div>
    @endif

    <div class="flex flex-col gap-6">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-5 shadow-xl shadow-[#0046FF]/20 bg-[#0046FF] hover:bg-[#001BB7]">
                {{ __('Resend Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-[10px] font-black uppercase tracking-[0.2em] text-[#001BB7]/40 hover:text-[#FF8040] transition-colors">
                {{ __('Maybe Later, Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>