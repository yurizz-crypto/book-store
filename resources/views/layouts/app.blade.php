<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'PageTurner Bookstore'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">

        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"></html>
        
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    
    <body class="font-sans antialiased bg-base-200 text-base-content">
        <div id="app" class="min-h-screen flex flex-col">
            @include('partials.navigation')

            @hasSection('header')
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-3xl font-bold text-gray-900 leading-tight">
                            @yield('header')
                        </h1>
                    </div>
                </header>
            @endif
            
            @include('partials.flash-messages')

            <main class="py-6 flex-grow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>

            @include('partials.footer')
        </div>

        @stack('scripts')
    </body>
</html>