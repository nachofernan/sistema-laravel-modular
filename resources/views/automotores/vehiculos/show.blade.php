<x-app-layout>
    <x-page-header title="{{ $vehiculo->nombre_completo }}">
        <x-slot:actions>
            @can('Automotores/Vehículos/Editar')
                <a href="{{ route('automotores.vehiculos.edit', $vehiculo) }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    Editar Vehículo
                </a>
            @endcan
            <a href="{{ route('automotores.vehiculos.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <!-- Banner de alerta de service -->
    @if($vehiculo->necesita_service)
    <div class="w-full max-w-7xl mx-auto mb-6">
        <div class="bg-red-50 border-l-4 border-red-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-red-800">
                                ¡Atención! El vehículo necesita service
                            </h3>
                                                         <div class="mt-1 text-sm text-red-700">
                                 <p>
                                     Han pasado {{ number_format($vehiculo->kilometraje - ($vehiculo->services->sortByDesc('fecha_service')->first()->kilometros ?? 0)) }} km desde el último service. 
                                 </p>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="w-full max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Datos Generales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Información del Vehículo</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Marca:</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $vehiculo->marca }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Modelo:</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $vehiculo->modelo }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Patente:</div>
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $vehiculo->patente }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Kilometraje:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ number_format($vehiculo->kilometraje) }} km
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Registrado:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ $vehiculo->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Última actualización:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {{ $vehiculo->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COPRES -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">COPRES (Compras de Combustible)</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $vehiculo->copres->count() }} registros
                            </span>
                            @can('Automotores/COPRES/Crear')
                                <a href="{{ route('automotores.copres.create') }}?vehiculo_id={{ $vehiculo->id }}" 
                                   class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md transition-colors">
                                    + Nueva COPRES
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="py-4">
                        @forelse ($vehiculo->copres as $copres)
                            <div class="flex items-center justify-between py-3 px-6 border-b border-gray-100 last:border-b-0
                            @if(!$copres->es_original)
                                bg-red-100
                            @endif
                            ">
                                <div class="flex-1">
                                    <!-- Fecha, Ticket y CUIT -->
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $copres->fecha->format('d/m/Y') }} - Ticket: {{ $copres->numero_ticket_factura }}
                                            <span class="text-xs text-gray-500 ml-2">
                                                @if($copres->es_original)
                                                    Original
                                                @else
                                                    ¡Copia!
                                                @endif
                                            </span>
                                        </div>
                                        @if($copres->cuit)
                                        <div class="text-xs text-gray-500">
                                            CUIT: {{ $copres->cuit }}
                                        </div>
                                        @endif
                                    </div>
                                    
                                                                         <!-- Litros y Precio por Litro -->
                                     <div class="flex items-center justify-between mb-2">
                                         <div class="text-sm text-gray-600">
                                             <span class="font-medium">{{ number_format($copres->litros, 2) }} L</span> 
                                             <span class="text-xs text-gray-500">@ ${{ number_format($copres->precio_x_litro, 2) }}/L</span>
                                         </div>
                                         <div class="text-sm font-medium text-gray-900">
                                             Total: ${{ number_format($copres->importe_final, 2) }}
                                         </div>
                                     </div>
                                    
                                    <!-- Información adicional -->
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-4">
                                            @if($copres->km_vehiculo)
                                            <span>KM: {{ number_format($copres->km_vehiculo) }}</span>
                                            @endif
                                            @if($copres->kz)
                                            <span>KZ: {{ $copres->kz }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            @if($copres->salida)
                                            <span>Salida: {{ $copres->salida->format('d/m/Y') }}</span>
                                            @endif
                                            @if($copres->reentrada)
                                            <span>Reentrada: {{ $copres->reentrada->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay COPRES registradas</h3>
                                <p class="text-gray-500 mb-4">Este vehículo aún no tiene registros de compras de combustible.</p>
                                @can('Automotores/COPRES/Crear')
                                    <a href="{{ route('automotores.copres.create') }}?vehiculo_id={{ $vehiculo->id }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Registrar Primera COPRES
                                    </a>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Estadísticas</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Total COPRES:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehiculo->copres->count() }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500">Total Services:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $vehiculo->services->count() }}</span>
                        </div>
                        
                        @if($vehiculo->copres->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Total litros:</span>
                                <span class="text-sm font-medium text-gray-900">{{ number_format($vehiculo->copres->sum('litros'), 2) }} L</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Total importe COPRES:</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($vehiculo->copres->sum('importe_final'), 2) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Promedio precio/litro:</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($vehiculo->copres->avg('precio_x_litro'), 2) }}</span>
                            </div>
                        @endif

                        @if($vehiculo->services->count() > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Total costo services:</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($vehiculo->services->sum('costo'), 2) }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500">Último service:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $vehiculo->services->sortByDesc('fecha_service')->first()->fecha_service->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
               

                <!-- Services -->
                <livewire:automotores.services.service-manager :vehiculo="$vehiculo" />
            </div>
        </div>
    </div>
</x-app-layout>
