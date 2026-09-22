<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Sidebar backdrop (mobile) -->
            <div x-show="sidebarOpen"
                 x-transition.opacity
                 @click="sidebarOpen = false"
                 class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden"
                 style="display: none;"></div>

            <!-- Main column -->
            <div class="lg:pl-64">
                <!-- Topbar -->
                @include('layouts.topbar')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm">
                        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-8">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <x-flash-message />
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
