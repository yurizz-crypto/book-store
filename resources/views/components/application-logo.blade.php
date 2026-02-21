<div {{ $attributes->merge(['class' => 'flex items-center group']) }}>
    <div class="relative flex items-center">
        <span class="text-3xl font-black uppercase tracking-tighter relative z-10 transition-all duration-500">
            {{-- Main Text with Brand Palette Gradient --}}
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#001BB7] to-[#0046FF] group-hover:from-[#FF8040] group-hover:to-[#ff9663] transition-all duration-700">
                Page
            </span>
            <span class="text-[#001BB7] group-hover:text-[#FF8040] transition-colors duration-700">
                Turner
            </span>
        </span>

        {{-- Animated Typing Cursor using Sunset Orange --}}
        <span class="ml-1 w-2 h-8 bg-[#FF8040] animate-logo-cursor shadow-[0_0_15px_rgba(255,128,64,0.5)]"></span>

        {{-- Background Glow Effect using Electric Blue --}}
        <div class="absolute -inset-4 bg-[#0046FF]/10 blur-2xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
    </div>
</div>

<style>
    @keyframes logo-cursor {
        0%, 100% { opacity: 1; transform: scaleY(1); }
        50% { opacity: 0.3; transform: scaleY(0.8); }
    }
    .animate-logo-cursor {
        animation: logo-cursor 1.2s ease-in-out infinite;
    }
    
    .group:hover span {
        letter-spacing: 0.025em;
    }
</style>