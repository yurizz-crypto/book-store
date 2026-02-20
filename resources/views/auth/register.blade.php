<x-guest-layout title="Register | PageTurner">
    {{-- Header Section --}}
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
            Create an account
        </h2>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        {{-- Name Section --}}
        <div class="grid grid-cols-1 gap-6"> {{-- Changed to grid-cols-1 --}}
            {{-- First Name --}}
            <div>
                <label for="first_name" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">
                    First Name
                </label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" required autofocus 
                    class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                    placeholder="First Name">
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            {{-- Middle Name --}}
            <div>
                <label for="middle_name" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">
                    Middle Name
                </label>
                <input id="middle_name" type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name ?? '') }}" 
                    class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                    placeholder="Middle Name (Optional)">
                <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
            </div>

            {{-- Last Name --}}
            <div>
                <label for="last_name" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">
                    Last Name
                </label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required 
                    class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                    placeholder="Last Name">
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

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
                       class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                       placeholder="Email address">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password Group (Grid for better spacing) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Password --}}
            <div>
                <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">
                    Password
                </label>
                <div class="relative">
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                           placeholder="Password">
                </div>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-xs font-black text-gray-500 uppercase tracking-[0.15em] mb-2 px-1">
                    Confirm
                </label>
                <div class="relative">
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           class="block w-full px-4 py-4 rounded-2xl border-gray-200 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-gray-400 border" 
                           placeholder="Confirm">
                </div>
            </div>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-1" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />

        {{-- Register Button --}}
        <div class="pt-2">
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-black rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-xl shadow-indigo-100 active:scale-[0.98]">
                <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                    <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </span>
                REGISTER
            </button>
        </div>

        {{-- Already registered? --}}
        <p class="mt-3 text-sm text-gray-600 text-center">
            Already have an account? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                Sign in here
            </a>
        </p>
    </form>
</x-guest-layout>