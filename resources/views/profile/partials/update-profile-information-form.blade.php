<section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <header class="border-b border-gray-50 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.963-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            {{ __('Account & Security Details') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Manage your identity and advanced security settings.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- First Name --}}
            <div>
                <x-input-label for="first_name" :value="__('First Name')" class="font-semibold" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('first_name', $user->first_name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            {{-- Last Name --}}
            <div>
                <x-input-label for="last_name" :value="__('Last Name')" class="font-semibold" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('last_name', $user->last_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            {{-- Middle Name --}}
            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" class="font-semibold" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('middle_name', $user->middle_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
            </div>

            {{-- Email Address --}}
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="font-semibold" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('email', $user->email)" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                {{-- Verification Logic --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-4 flex flex-col items-start gap-2">
                        <p class="text-sm text-amber-600 font-medium italic">
                            {{ __('Your email address is currently unverified.') }}
                        </p>
                        
                        <button form="send-verification" type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-400 hover:bg-amber-500 text-amber-950 text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1 1 0 0 0 .894 0L19 7.161V6a2 2 0 0 0-2-2H3Z" />
                                <path d="m19 8.839-7.77 3.885a2.001 2.001 0 0 1-1.79 0L2 8.839V14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.839Z" />
                            </svg>
                            {{ __('Send Verification Link') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-semibold text-emerald-600">
                                {{ __('A fresh verification link has been sent to your inbox.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Lab 4: Two-Factor Authentication Integration --}}
            <div class="md:col-span-2 mt-4 pt-6 border-t border-gray-50">
                <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-4">
                    {{ __('Advanced Protection') }}
                </h3>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="two_factor_enabled" 
                               name="two_factor_enabled" 
                               type="checkbox" 
                               value="1"
                               {{ Auth::user()->two_factor_enabled ? 'checked' : '' }}
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 transition-all cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="two_factor_enabled" class="font-bold text-gray-900 cursor-pointer">
                            {{ __('Enable Two-Factor Authentication (2FA)') }}
                        </label>
                        <p class="text-gray-500 italic">
                            {{ __('You will be required to enter an email-based code during login.') }}
                        </p>
                    </div>
                </div>

                @if (Auth::user()->two_factor_enabled)
                    <div class="mt-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center gap-3">
                        <div class="bg-emerald-500 p-1.5 rounded-full text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-emerald-800">
                            {{ __('Account status: 2FA Protected') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="hover:bg-indigo-700 rounded-xl px-6 py-2.5 transition-all shadow-md shadow-indigo-100">
                {{ __('Update Profile & Security') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Saved successfully') }}
                </span>
            @endif
        </div>
    </form>
</section>

{{-- Hidden form for email verification submission --}}
<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>