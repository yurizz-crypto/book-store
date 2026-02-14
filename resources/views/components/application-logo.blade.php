<div {{ $attributes->merge(['class' => 'flex items-center group']) }}>
    <div class="relative flex items-center">
        <span class="text-2xl font-black uppercase tracking-tighter relative z-10">
            {{-- Main Text with Indigo-to-Amber Gradient --}}
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-700 to-indigo-500 group-hover:from-amber-400 group-hover:to-orange-500 transition-all duration-700">
                Page
            </span>
            <span class="text-indigo-900 group-hover:text-amber-500 transition-colors duration-700">
                Turner
            </span>
        </span>

        {{-- Animated Typing Cursor --}}
        <span class="ml-1 w-1.5 h-8 bg-amber-400 animate-logo-cursor"></span>

        {{-- Background Glow Effect --}}
        <div class="absolute -inset-2 bg-indigo-400/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
    </div>
</div>

<style>
    @keyframes logo-cursor {
        0%, 100% { opacity: 1; transform: scaleY(1); }
        50% { opacity: 0; transform: scaleY(0.7); }
    }
    .animate-logo-cursor {
        animation: logo-cursor 1.2s ease-in-out infinite;
    }
    
    /* Optional: Letter spacing expansion on hover */
    .group:hover span {
        letter-spacing: 0.05em;
    }
</style>