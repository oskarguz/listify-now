<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Listify Now' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=mulish:400,500,600&display=swap" rel="stylesheet"/>
        {{ $css ?? '' }}

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-background-primary flex flex-col">
            @include('layouts.header')

            <!-- Page Content -->
            <main class="flex-1 flex flex-wrap pt-16">
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>

        {{ $javascript ?? '' }}
    </body>
</html>
