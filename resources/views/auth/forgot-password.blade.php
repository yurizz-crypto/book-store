<x-guest-layout>
    {{-- Header Section --}}
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Reset Password
        </h2>
        <p class="mt-3 text-sm text-gray-600 px-4">
            Forgot your password? No problem. Enter your email and we'll send you a link to choose a new one.
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">
                Email Address
            </label>
            <div class="relative">
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                       placeholder="Enter your registered email">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-black rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-xl shadow-indigo-100 active:scale-[0.98]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </span>
                SEND RESET LINK
            </button>
        </div>

        {{-- Return to Login --}}
        <p class="mt-3 text-sm text-gray-600 text-center">
            Remembered it? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                Back to Login
            </a>
        </p>
    </form>
</x-guest-layout>