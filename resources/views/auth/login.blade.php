<x-guest-layout title="Login | PageTurner">
    {{-- Header Section --}}
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Welcome back
        </h2>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2">
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
                       placeholder="Email">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em]">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-500 transition-colors" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" 
                       type="password" 
                       name="password" 
                       required 
                       class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                       placeholder="Password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 transition cursor-pointer" name="remember">
                <span class="ms-3 text-sm font-semibold text-gray-600 group-hover:text-gray-900 transition-colors">
                    Keep me signed in
                </span>
            </label>
        </div>

        {{-- Login Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-black rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-xl shadow-indigo-100 active:scale-[0.98]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                LOG IN
            </button>
        </div>

        <p class="mt-3 text-sm text-gray-600 text-center">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                Create an account
            </a>
        </p>
    </form>
</x-guest-layout>