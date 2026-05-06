<section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <header class="border-b border-gray-50 pb-4 mb-6">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            {{ __('Shipping Address') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __("Update your primary shipping destination for book deliveries.") }}
        </p>
    </header>

    @php $address = auth()->user()->addresses()->where('is_default', true)->first(); @endphp

    <form method="post" action="{{ route('addresses.update') }}" class="space-y-6">
        @csrf @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Street Address (Name and ID Updated) --}}
                <div class="md:col-span-2">
                    <x-input-label for="address_line_1" :value="__('Street Address')" class="font-semibold" />
                    <x-text-input id="address_line_1" name="address_line_1" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 rounded-xl" :value="old('address_line_1', $address->address_line_1 ?? '')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('address_line_1')" />
                </div>

                {{-- City --}}
                <div>
                    <x-input-label for="city" :value="__('City')" class="font-semibold" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 rounded-xl" :value="old('city', $address->city ?? '')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                </div>

                {{-- State (NEW FIELD ADDED) --}}
                <div>
                    <x-input-label for="state" :value="__('State')" class="font-semibold" />
                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 rounded-xl" :value="old('state', $address->state ?? '')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('state')" />
                </div>

                {{-- Postal Code --}}
                <div>
                    <x-input-label for="postal_code" :value="__('Postal Code')" class="font-semibold" />
                    <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 rounded-xl" :value="old('postal_code', $address->postal_code ?? '')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                </div>

                {{-- Country --}}
                <div>
                    <x-input-label for="country" :value="__('Country')" class="font-semibold" />
                    <x-text-input id="country" name="country" type="text" class="mt-1 block w-full border-gray-200 focus:ring-indigo-500 rounded-xl" :value="old('country', $address->country ?? 'Philippines')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('country')" />
                </div>
            </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="hover:bg-indigo-700 rounded-xl px-6 py-2.5 transition-all shadow-md shadow-indigo-100">
                {{ __('Save Address') }}
            </x-primary-button>

            @if (session('status') === 'address-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600 font-medium">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>