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
        <form wire:submit.prevent="agregar" class="flex space-x-3">
            <div class="flex-1">
                <label for="user_select" class="sr-only">Seleccionar usuario</label>
                <select wire:model="user_id" 
                        id="user_select"
                        class="block text-sm w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Seleccionar usuario para invitar...</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">
                            {{ $usuario->legajo }} - {{ $usuario->realname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" 
                    class="inline-flex text-sm items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!$wire.user_id">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Invitar
            </button>
        </form>
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
                    <div class="text-sm text-blue-600">Invitados</div>
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
                            @if($invitacion->presente)
                                <p class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Presente
                                </p>
                            @else
                                <p class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    Ausente
                                </p>
                            @endif
                        </div>
                    </div>
                
                    <!-- Acciones -->
                    @can('Capacitaciones/Capacitaciones/Editar')
                    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
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

            <!-- Acciones masivas -->
            @can('Capacitaciones/Capacitaciones/Editar')
            @if($capacitacion->invitaciones->count() > 1)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-900">Acciones masivas:</h4>
                    <div class="flex space-x-2">
                        <button wire:click="marcarTodosPresentes" 
                                onclick="return confirm('¿Marcar todos como presentes?')"
                                class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 text-xs font-medium rounded-md hover:bg-green-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Todos Presentes
                        </button>
                        <button wire:click="marcarTodosAusentes" 
                                onclick="return confirm('¿Marcar todos como ausentes?')"
                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-xs font-medium rounded-md hover:bg-red-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Todos Ausentes
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endcan

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
                <p class="text-sm text-gray-400">Selecciona un usuario del menú desplegable superior para invitarlo.</p>
                @else
                <p class="text-sm text-red-500">No hay usuarios disponibles para invitar.</p>
                @endif
                @endcan
            </div>
        @endif
    </div>
</div>