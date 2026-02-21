<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'PageTurner Bookstore'))</title>

        {{-- Fonts --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        {{-- Added heavier weights for that "font-black" look --}}
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    
    {{-- Body background uses a very subtle tint of your Cream palette for a premium feel --}}
    <body class="font-sans antialiased bg-[#F5F1DC]/30 text-[#001BB7]">
        <div id="app" class="min-h-screen flex flex-col">
            {{-- Navigation --}}
            @include('partials.navigation')

            {{-- Header Section --}}
            @hasSection('header')
                <header class="bg-white border-b border-[#0046FF]/10 shadow-sm">
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-3xl font-black text-[#001BB7] leading-tight uppercase tracking-tighter">
                            @yield('header')
                        </h1>
                    </div>
                </header>
            @endif
            
            {{-- Flash Messages --}}
            @include('partials.flash-messages')

            {{-- Main Content --}}
            <main class="py-10 flex-grow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            @include('partials.footer')
        </div>

        @stack('scripts')
    </body>
</html>