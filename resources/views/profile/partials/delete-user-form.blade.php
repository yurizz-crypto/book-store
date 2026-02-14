<section class="bg-red-50/50 p-6 rounded-2xl border border-red-100">
    <header class="mb-6">
        <h2 class="text-xl font-bold text-red-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            {{ __('Danger Zone') }}
        </h2>
        <p class="mt-1 text-sm text-red-600/80">
            {{ __('Deleting your account is permanent. All associated IT project data and library history will be wiped.') }}
        </p>
    </header>

    <x-danger-button
        class="rounded-xl px-6 py-2.5 shadow-md shadow-red-100"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Delete Permanently') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf @method('delete')

            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                {{ __('Final Confirmation') }}
            </h2>

            <p class="text-gray-500 mb-6">
                {{ __('To proceed, please enter your account password. This action cannot be undone.') }}
            </p>

            <div class="mb-6">
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full border-gray-200 rounded-xl py-3"
                    placeholder="{{ __('Verify your password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl border-gray-200 px-6">
                    {{ __('Go Back') }}
                </x-secondary-button>

                <x-danger-button class="rounded-xl px-6">
                    {{ __('Confirm Deletion') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>