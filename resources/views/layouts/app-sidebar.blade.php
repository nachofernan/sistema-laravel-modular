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
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false, openModule: null }">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed top-4 left-4 z-20">
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md bg-white shadow-md">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-sm border-r border-gray-200 fixed h-full z-10 transform lg:transform-none lg:translate-x-0 transition-transform duration-300 ease-in-out"
             :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
             @click.away="sidebarOpen = false">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <a href="{{ route('home') }}" title="Página principal">
                    <x-application-logo />
                </a>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 px-3 space-y-1 overflow-y-auto  h-full pb-20 flex-1 flex flex-col">
                <div class="flex-1">
                    @include('layouts.partials.sidebar-navigation')
                </div>
                
                {{-- FOOTER PEGADO AL FONDO --}}
                <div class="mt-auto">
                    <div class="border-t border-gray-200 mb-2"></div>
                    <div class="px-0 py-2">
                        <p class="text-xs text-gray-400 text-center">
                            versión 1.0.0 - Nacho
                        </p>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Page Content (sin header) -->
            <main class="p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>