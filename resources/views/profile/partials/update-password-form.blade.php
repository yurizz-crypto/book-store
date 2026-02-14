<section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <header class="border-b border-gray-50 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
            {{ __('Password & Security') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Update your credentials to keep your account safe.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="max-w-xl space-y-6">
        @csrf @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="font-semibold" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full border-gray-200 rounded-xl" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" class="font-semibold" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full border-gray-200 rounded-xl" />
            </div>
            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" class="font-semibold" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full border-gray-200 rounded-xl" />
            </div>
        </div>
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="bg-slate-800 hover:bg-slate-900 rounded-xl shadow-md">
                {{ __('Update Password') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-emerald-600 font-medium">
                    {{ __('Password updated.') }}
                </p>
            @endif
        </div>
    </form>
</section>