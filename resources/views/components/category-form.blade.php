@props(['category' => null, 'action'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($category) @method('PUT') @endif

    {{-- Category Name --}}
    <div>
        <x-input-label for="name" :value="__('Category Name')" class="font-semibold" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
            :value="old('name', $category?->name)" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    {{-- Description --}}
    <div>
        <x-input-label for="description" :value="__('Description (Optional)')" class="font-semibold" />
        <textarea name="description" id="description" rows="4"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $category?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
        <a href="{{ route('categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium transition">
            {{ __('Cancel') }}
        </a>
        <x-primary-button>
            {{ $category ? __('Update Category') : __('Create Category') }}
        </x-primary-button>
    </div>
</form>