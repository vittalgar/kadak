<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Project Kadak' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js" defer></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans text-gray-900 antialiased min-h-dvh">
    <div class="relative min-h-dvh flex flex-col sm:justify-center items-center bg-gray-100">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/login-bg.jpeg') }}" alt="Background" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>

        {{-- A container here will constrain the width of your spin page --}}
        <div class="relative z-10 w-full">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts
    @stack('scripts')
</body>

</html>
