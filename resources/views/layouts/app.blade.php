<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script>
    if (
        localStorage.getItem('theme') === 'dark' ||
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('dark');
    }
</script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-black dark:bg-gray-900 dark:text-white font-sans antialiased">

    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-grow px-4 py-6">
            {{ $slot }}
        </main>
    </div>

    <!-- Dark Mode Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('darkToggle');
            const html = document.documentElement;

            // Apply saved theme on page load
            if (localStorage.getItem('theme') === 'dark') {
                html.classList.add('dark');
            }

            // Toggle dark mode
            toggle?.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('theme',
                    html.classList.contains('dark') ? 'dark' : 'light'
                );
            });
        });
    </script>

</body>
</html>
