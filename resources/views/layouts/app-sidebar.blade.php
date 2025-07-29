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
    <div class="min-h-screen flex" 
         x-data="{ 
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
            init() {
                // Restaurar estado al cargar
                this.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            },
            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            }
         }">
        
        <!-- Sidebar -->
        <div class="bg-white shadow-sm border-r border-gray-200 fixed h-full z-10 transition-all duration-300 ease-in-out"
             :class="{
                'w-64': !sidebarCollapsed,
                'w-12': sidebarCollapsed
             }">
            
            <!-- Desktop toggle button (integrado en el sidebar) -->
            <div class="absolute top-4 z-20"
                 :class="{
                    'left-2': !sidebarCollapsed,
                    'right-2': sidebarCollapsed
                 }">
                <button @click="toggleSidebar()" class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarCollapsed">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarCollapsed">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200"
                 x-show="!sidebarCollapsed">
                <a href="{{ route('home') }}" title="Página principal">
                    <x-application-logo />
                </a>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 px-3 space-y-1 overflow-y-auto h-full pb-20 flex-1 flex flex-col"
                 :class="{ 'px-1': sidebarCollapsed }">
                <div class="flex-1" x-show="!sidebarCollapsed">
                    @include('layouts.partials.sidebar-navigation')
                </div>
                
                {{-- FOOTER PEGADO AL FONDO --}}
                <div class="mt-auto" x-show="!sidebarCollapsed">
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
        <div class="flex-1"
             :class="{
                'ml-64': !sidebarCollapsed,
                'ml-12': sidebarCollapsed
             }">
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