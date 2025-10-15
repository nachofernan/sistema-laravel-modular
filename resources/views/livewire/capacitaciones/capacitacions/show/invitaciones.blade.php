<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900">Gestión de Invitaciones</h3>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ $capacitacion->invitaciones->count() }} invitados
            </span>
        </div>
    </div>

    <!-- Agregar nuevo invitado -->
    @can('Capacitaciones/Capacitaciones/Editar')
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <button wire:click="$set('open', true)" 
                class="inline-flex text-sm items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Agregar Invitaciones
        </button>
    </div>
    @endcan

    <!-- Lista de invitados -->
    <div class="p-6">
        @if($capacitacion->invitaciones->count() > 0)
            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $capacitacion->invitaciones->count() }}
                    </div>
                    <div class="text-sm text-blue-600">Total</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">
                        {{ $capacitacion->invitaciones->where('presente', 1)->count() }}
                    </div>
                    <div class="text-sm text-green-600">Presentes</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-red-600">
                        {{ $capacitacion->invitaciones->where('presente', 0)->count() }}
                    </div>
                    <div class="text-sm text-red-600">Ausentes</div>
                </div>
            </div>

            <!-- Información de tipos -->
            @php
                $presenciales = $capacitacion->invitaciones->where('tipo', 'presencial')->count();
                $virtuales = $capacitacion->invitaciones->where('tipo', 'virtual')->count();
            @endphp
            @if($presenciales > 0 || $virtuales > 0)
            <div class="mb-4 text-sm text-gray-600">
                <span class="font-medium">Tipos de participación:</span>
                @if($presenciales > 0)
                    <span class="text-purple-600">{{ $presenciales }} presencial{{ $presenciales > 1 ? 'es' : '' }}</span>
                @endif
                @if($presenciales > 0 && $virtuales > 0)
                    <span class="mx-1">•</span>
                @endif
                @if($virtuales > 0)
                    <span class="text-orange-600">{{ $virtuales }} virtual{{ $virtuales > 1 ? 'es' : '' }}</span>
                @endif
            </div>
            @endif

            <!-- Lista de usuarios -->
            <div class="space-y-3">
                @foreach($capacitacion->invitaciones as $invitacion)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors gap-2 sm:gap-4">
                    <!-- Información del usuario -->
                    <div class="flex-1 min-w-0">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $invitacion->usuario->realname }}
                            </h4>
                            <p class="text-gray-500 text-xs">Legajo: {{ $invitacion->usuario->legajo }}</p>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @if($invitacion->presente)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Presente
                                    </span>
                                    
                                    <!-- Mostrar tipo solo cuando está presente -->
                                    @if($invitacion->tipo === 'presencial')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Presencial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            Virtual
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Ausente
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                
                    <!-- Acciones -->
                    @can('Capacitaciones/Capacitaciones/Editar')
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                        <!-- Cambiar tipo solo cuando está presente -->
                        @if($invitacion->presente)
                        <select wire:change="cambiarTipo({{ $invitacion->id }}, $event.target.value)" 
                                class="text-xs px-2 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            <option value="presencial" {{ $invitacion->tipo === 'presencial' ? 'selected' : '' }}>Presencial</option>
                            <option value="virtual" {{ $invitacion->tipo === 'virtual' ? 'selected' : '' }}>Virtual</option>
                        </select>
                        @endif
                        
                        <button wire:click="presente({{ $invitacion->id }})"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md transition-colors
                                   {{ $invitacion->presente ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            @if($invitacion->presente)
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Marcar Ausente
                            @else
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Marcar Presente
                            @endif
                        </button>
                
                        <button wire:click="quitar({{ $invitacion->id }})"
                            onclick="return confirm('¿Estás seguro de quitar esta invitación?')"
                            class="inline-flex items-center p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                    @endcan
                </div>
                @endforeach
            </div>

            

        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay invitados</h3>
                <p class="text-gray-500 mb-6">Agrega usuarios a esta capacitación para comenzar a gestionar las invitaciones.</p>
                @can('Capacitaciones/Capacitaciones/Editar')
                @if($usuarios->count() > 0)
                <p class="text-sm text-gray-400">Haz clic en "Agregar Invitaciones" para seleccionar usuarios.</p>
                @else
                <p class="text-sm text-red-500">No hay usuarios disponibles para invitar.</p>
                @endif
                @endcan
            </div>
        @endif
    </div>

    <!-- Modal para agregar invitaciones -->
    @can('Capacitaciones/Capacitaciones/Editar')
    <div x-data="{ open: @entangle('open'), selectAll: false }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- Modal panel -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false">
                
                <!-- Header fijo -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Seleccionar Usuarios para Invitar
                            </h3>
                        </div>
                        <button @click="open = false" 
                                class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body scrolleable -->
                <div class="px-6 py-4 max-h-[75vh] overflow-y-auto">
                    @if($usuarios->count() > 0)
                        <!-- Seleccionar todos -->
                        <div class="mb-4 pb-3 border-b border-gray-200 sticky top-0 bg-white z-10">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-2 mb-2">
                                <div class="col-span-1 sm:col-span-2">
                                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por legajo o nombre..." class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <select wire:model.live="sedeId" class="w-full text-sm px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        <option value="">Todas las sedes</option>
                                        @isset($sedes)
                                            @foreach($sedes as $sede)
                                                <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded-md transition-colors">
                                <input type="checkbox" 
                                       x-model="selectAll"
                                       @change="
                                           if(selectAll) {
                                               $wire.selectedUsers = [{{ $usuarios->pluck('id')->implode(',') }}];
                                           } else {
                                               $wire.selectedUsers = [];
                                           }
                                       "
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm font-semibold text-gray-900">Seleccionar todos ({{ $usuarios->count() }} usuarios)</span>
                            </label>
                        </div>

                        <!-- Lista de usuarios -->
                        <div class="space-y-2">
                            @foreach($usuarios as $usuario)
                            <label wire:key="usuario-{{ $usuario->id }}" class="flex items-center cursor-pointer hover:bg-gray-50 p-3 rounded-md transition-colors">
                                <input type="checkbox" 
                                       value="{{ $usuario->id }}"
                                       wire:model="selectedUsers"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-gray-900">{{ $usuario->realname }}</span>
                                    <span class="text-xs text-gray-500 ml-2">Legajo: {{ $usuario->legajo }}</span>
                                    @if($usuario->sede)
                                        <span class="text-xs text-gray-500 ml-2">Sede: {{ $usuario->sede->nombre }}</span>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500">No hay usuarios disponibles para invitar.</p>
                        </div>
                    @endif
                </div>

                <!-- Footer con botones -->
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <span class="text-sm text-gray-600">
                        <span x-show="$wire.selectedUsers.length > 0" x-cloak>
                            <span x-text="$wire.selectedUsers.length"></span> usuario(s) seleccionado(s)
                        </span>
                    </span>
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                @click="open = false; $wire.selectedUsers = []; selectAll = false"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors">
                            Cancelar
                        </button>
                        <button type="button" 
                                wire:click="agregar"
                                :disabled="$wire.selectedUsers.length === 0"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Invitaciones
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>