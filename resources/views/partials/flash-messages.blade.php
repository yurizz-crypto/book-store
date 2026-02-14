<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
    @if(session('success'))
        <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm shadow-emerald-50 transition-all">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-bold tracking-tight">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="bg-rose-50 border border-rose-100 text-rose-800 px-6 py-4 rounded-2xl shadow-sm shadow-rose-50 transition-all">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-black uppercase tracking-widest">Action Failed</span>
            </div>

            @if(session('error'))
                <p class="mt-2 text-sm font-medium ml-8">{{ session('error') }}</p>
            @endif

            @if($errors->any())
                <ul class="mt-2 space-y-1 ml-8 text-sm font-medium list-disc list-inside opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
</div>