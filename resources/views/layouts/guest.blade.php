@props([
    'title' => 'PageTurner'
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#001BB7] antialiased">
        {{-- Background uses your Cream palette color --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#F5F1DC]">
            <div class="mb-8 transition-transform hover:scale-105 duration-300 pt-10">
                <a href="/" class="flex flex-col items-center gap-2">
                    <x-application-logo/>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white shadow-2xl shadow-[#001BB7]/10 overflow-hidden rounded-[3rem] border border-[#0046FF]/5">
                <div class="mb-6 text-center">
                    <h2 class="text-sm font-black uppercase tracking-[0.2em] text-[#0046FF]/40">Authentication</h2>
                </div>
                
                {{ $slot }}
            </div>

            {{-- Subtle footer for guest pages --}}
            <div class="mt-8 pb-10">
                <a href="/" class="text-xs font-black uppercase tracking-widest text-[#001BB7]/40 hover:text-[#FF8040] transition-colors">
                    &larr; Back to Bookstore
                </a>
            </div>
        </div>
    </body>
</html>