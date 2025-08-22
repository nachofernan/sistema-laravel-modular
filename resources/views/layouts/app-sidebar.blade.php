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
            sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' || false,
            sidebarLocked: localStorage.getItem('sidebarLocked') === 'true' || false,
            sidebarHovered: false,
            hoverTimeout: null,
            init() {
                // Restaurar estado al cargar, por defecto sidebar abierto si es la primera vez
                this.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                this.sidebarLocked = localStorage.getItem('sidebarLocked') === 'true';
                
                // Si es la primera vez (no hay valor en localStorage), sidebar abierto
                if (localStorage.getItem('sidebarCollapsed') === null) {
                    this.sidebarCollapsed = false;
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            },
            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
            },
            toggleLock() {
                this.sidebarLocked = !this.sidebarLocked;
                localStorage.setItem('sidebarLocked', this.sidebarLocked);
            },
            onSidebarMouseEnter() {
                if (!this.sidebarLocked) {
                    // Limpiar timeout si existe
                    if (this.hoverTimeout) {
                        clearTimeout(this.hoverTimeout);
                        this.hoverTimeout = null;
                    }
                    this.sidebarHovered = true;
                }
            },
            onSidebarMouseLeave() {
                if (!this.sidebarLocked) {
                    // Establecer timeout de 1 segundo
                    this.hoverTimeout = setTimeout(() => {
                        this.sidebarHovered = false;
                    }, 1000);
                }
            },
            getSidebarWidth() {
                if (this.sidebarLocked) {
                    return this.sidebarCollapsed ? 'w-12' : 'w-64';
                } else {
                    return this.sidebarHovered ? 'w-64' : 'w-12';
                }
            },
            getContentMargin() {
                if (this.sidebarLocked) {
                    return this.sidebarCollapsed ? 'ml-12' : 'ml-64';
                } else {
                    return this.sidebarHovered ? 'ml-64' : 'ml-12';
                }
            },
            isContentVisible() {
                if (this.sidebarLocked) {
                    return !this.sidebarCollapsed;
                } else {
                    return this.sidebarHovered;
                }
            }
         }">
        
        <!-- Sidebar -->
        <div class="bg-white shadow-sm border-r border-gray-200 fixed h-full z-10 transition-all duration-300 ease-in-out"
             :class="getSidebarWidth()"
             @mouseenter="onSidebarMouseEnter()"
             @mouseleave="onSidebarMouseLeave()">
            
            <!-- Toggle button (candado) en el borde izquierdo -->
            <div class="absolute top-4 left-6 z-20 transform -translate-x-1/2">
                <button @click="toggleLock()" 
                        class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 shadow-sm transition-colors border border-gray-200"
                        :title="sidebarLocked ? 'Desbloquear sidebar' : 'Bloquear sidebar'">
                    <!-- Candado cerrado -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="sidebarLocked">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <!-- Candado abierto -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarLocked">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200"
                 x-show="isContentVisible()">
                <a href="{{ route('home') }}" title="Página principal">
                    <x-application-logo />
                </a>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 px-3 space-y-1 overflow-y-auto h-full pb-20 flex-1 flex flex-col"
                 :class="{ 'px-1': !isContentVisible() }">
                <div class="flex-1" x-show="isContentVisible()">
                    @include('layouts.partials.sidebar-navigation-new')
                </div>
                
                {{-- FOOTER PEGADO AL FONDO --}}
                <div class="mt-auto" x-show="isContentVisible()">
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
        <div class="flex-1 overflow-x-hidden transition-all duration-300 ease-in-out"
             :class="getContentMargin()">
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