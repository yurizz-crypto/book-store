<section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <header class="border-b border-gray-50 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.963-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            {{ __('Account Details') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Manage your username and primary contact email.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="first_name" :value="__('First Name')" class="font-semibold" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('first_name', $user->first_name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" class="font-semibold" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('last_name', $user->last_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" class="font-semibold" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('middle_name', $user->middle_name)" />
                <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" class="font-semibold" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl" :value="old('email', $user->email)" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 rounded-xl px-6 py-2.5 transition-all shadow-md shadow-indigo-100">
                {{ __('Update Profile') }}
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