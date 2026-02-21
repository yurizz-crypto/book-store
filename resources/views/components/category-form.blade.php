@props(['category' => null, 'action'])

<form action="{{ $action }}" method="POST" class="space-y-8 bg-white p-8 rounded-[2.5rem] border border-[#0046FF]/10 shadow-xl shadow-[#001BB7]/5">
    @csrf
    @if($category) @method('PUT') @endif

    <div class="mb-2">
        <h2 class="text-xs font-black uppercase tracking-[0.2em] text-[#0046FF]/40">Category Management</h2>
    </div>

    {{-- Category Name --}}
    <div>
        <x-input-label for="name" :value="__('Category Name')" class="font-black uppercase tracking-widest text-[10px] text-[#001BB7] ml-1" />
        <input id="name" name="name" type="text" 
            class="mt-2 block w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-bold text-[#001BB7] placeholder-[#001BB7]/30 transition-all py-3" 
            value="{{ old('name', $category?->name) }}" 
            placeholder="e.g., Science Fiction"
            required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2 ml-1" />
    </div>

    {{-- Description --}}
    <div>
        <x-input-label for="description" :value="__('Description (Optional)')" class="font-black uppercase tracking-widest text-[10px] text-[#001BB7] ml-1" />
        <textarea name="description" id="description" rows="5"
            placeholder="Briefly describe what readers can expect from this genre..."
            class="mt-2 block w-full bg-[#F5F1DC]/30 border-[#0046FF]/10 focus:border-[#0046FF] focus:ring-[#0046FF] rounded-2xl shadow-sm font-medium text-[#001BB7] placeholder-[#001BB7]/30 transition-all">{{ old('description', $category?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2 ml-1" />
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-6 pt-8 border-t border-[#0046FF]/5">
        <a href="{{ route('categories.index') }}" class="text-xs font-black uppercase tracking-widest text-[#001BB7]/40 hover:text-[#FF8040] transition-colors">
            {{ __('Cancel') }}
        </a>
        
        <button type="submit" class="bg-[#001BB7] text-[#F5F1DC] px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-[#001BB7]/20 hover:bg-[#0046FF] transition-all transform active:scale-95">
            {{ $category ? __('Update Category') : __('Create Category') }}
        </button>
    </div>
</form>