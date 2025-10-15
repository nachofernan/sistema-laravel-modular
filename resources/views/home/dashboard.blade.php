<x-app-layout>
    <x-page-header title="Dashboard Personal">
        <x-slot:actions>
            <span class="text-sm text-gray-500">
                Bienvenido, {{ Auth::user()->realname }}
            </span>
        </x-slot:actions>
    </x-page-header>

    @if (session('mensaje_verde'))
        <div class="mb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('mensaje_verde') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-400 hover:text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Columna Izquierda - Tickets y Gestión -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Componente: Nuevo Ticket -->
                @livewire('dashboard.nuevo-ticket')
            
            <!-- Componente: Mi Inventario -->
                @livewire('dashboard.mi-inventario')

            <!-- Componente: Mis Capacitaciones -->
                {{-- @livewire('dashboard.mis-capacitaciones') --}}

        </div>

        <!-- Columna Derecha - Información Personal -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Componente: Mis Tickets -->
                @livewire('dashboard.mis-tickets')
            
            <!-- Componente: Chat Assistant -->
               {{--  @livewire('dashboard.chat-assistant') --}}
            
        </div>
    </div>

    <!-- Modal Global para Tickets -->
    @livewire('dashboard.ticket-modal')
</x-app-layout>