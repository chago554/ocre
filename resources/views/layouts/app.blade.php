<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="ocre">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-base-100" x-data="{ open: true }">
    <div class="flex min-h-screen">
        @include('layouts.navigation')

        <main class="flex-1 transition-all duration-300 ease-in-out"
              :class="open ? 'ml-64' : 'ml-20'"
              :style="open ? 'max-width:calc(100vw - 16rem)' : 'max-width:calc(100vw - 5rem)'">
            <div class="p-10">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () =>
            Toast.success("{{ session('success') }}")
        );
    </script>
    @endif
</body>

</html>
