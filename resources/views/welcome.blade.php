<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Project Kadak</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
    <div class="relative min-h-screen w-full flex items-center justify-center bg-gray-800 overflow-hidden">

        <div class="absolute inset-0 z-0">
            <img class="w-full h-full object-cover" src="images/bg.png" alt="Tea Background">
            <div class="absolute inset-0 bg-black opacity-70"></div>
        </div>

        <div class="relative z-10 text-center text-white px-6">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Project Kadak Logo" class="w-40 h-auto">
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight">
                Welcome to <span class="text-indigo-400">Bharath Beverages</span>
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-lg sm:text-xl text-gray-300">
                The revolutionary dynamic rewards platform designed to connect with customers and drive brand loyalty
                across the nation.
            </p>
        </div>
    </div>
</body>

</html>
