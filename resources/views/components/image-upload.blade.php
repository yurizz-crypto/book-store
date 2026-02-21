@props(['name', 'id' => null, 'value' => null])

<div x-data="imageViewer('{{ $value ? asset('storage/' . $value) : '' }}')" 
     class="p-6 bg-[#F5F1DC]/20 border-2 border-dashed border-[#0046FF]/20 rounded-[2rem]">
    
    <div class="flex flex-col md:flex-row items-center gap-8">
        {{-- Preview Area --}}
        <div class="relative group shrink-0">
            <div class="h-40 w-28 bg-white rounded-xl shadow-lg overflow-hidden border border-[#0046FF]/10 ring-4 ring-white group-hover:ring-[#0046FF]/20 transition-all flex items-center justify-center">
                <template x-if="imageUrl">
                    <img :src="imageUrl" class="h-full w-full object-cover">
                </template>
                
                <template x-if="!imageUrl">
                    <div class="flex flex-col items-center opacity-20">
                        <svg class="w-8 h-8 text-[#001BB7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </template>
            </div>
            
            <template x-if="imageUrl">
                <span class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-[#001BB7] text-white text-[8px] font-black uppercase tracking-widest px-2 py-1 rounded-full shadow-sm">
                    Preview
                </span>
            </template>
        </div>

        {{-- Upload Controls --}}
        <div class="flex-1 w-full">
            <label for="{{ $id ?? $name }}" class="flex flex-col items-center justify-center w-full h-32 cursor-pointer hover:bg-[#F5F1DC]/40 transition-colors rounded-2xl border-2 border-[#0046FF]/10 border-dotted group">
                <div class="flex flex-col items-center justify-center pt-2">
                    <svg class="w-8 h-8 mb-2 text-[#FF8040] group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <p class="text-[10px] text-[#001BB7] font-black uppercase tracking-widest">Select New Artwork</p>
                </div>
                <input type="file" 
                       name="{{ $name }}" 
                       id="{{ $id ?? $name }}" 
                       accept="image/*" 
                       class="hidden" 
                       @change="fileChosen">
            </label>
        </div>
    </div>
</div>

{{-- Script bundled into the component - Alpine.js --}}
@once
<script>
    function imageViewer(initialUrl = '') {
        return {
            imageUrl: initialUrl,
            fileChosen(event) {
                if (! event.target.files.length) return;

                let file = event.target.files[0];
                let reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = e => this.imageUrl = e.target.result;
            }
        }
    }
</script>
@endonce