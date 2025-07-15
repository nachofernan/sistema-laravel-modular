<x-app-layout>
    <x-page-header title="{{ $elemento->codigo }}">
        <x-slot:actions>
            @can('Inventario/Elementos/Editar')
                <a href="{{ route('inventario.elementos.edit', $elemento) }}" 
                   class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition-colors">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-7xl mx-auto">
        <!-- Información básica del elemento -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    @if ($elemento->categoria->icono)
                        <div class="mr-3">
                            {!! $elemento->categoria->icono !!}
                        </div>
                    @endif
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $elemento->codigo }}</h2>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <a href="{{ route('inventario.categorias.show', $elemento->categoria) }}"
                               class="text-blue-600 hover:text-blue-800 transition-colors">
                                {{ $elemento->categoria->nombre }}
                            </a>
                            <span>•</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                         {{ $elemento->estado->id == 1 ? 'bg-green-100 text-green-800' : 
                                            ($elemento->estado->id == 2 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $elemento->estado->nombre }}
                            </span>
                        </div>
                    </div>
                </div>
                @if ($elemento->user)
                    @livewire('inventario.elementos.show.button-firma', ['elemento' => $elemento], key($elemento->id . microtime(true)))
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Información General -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                </div>

                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Cargado:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ Carbon\Carbon::create($elemento->created_at)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Usuario:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $elemento->user->realname ?? 'Sin asignar' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Sede:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $elemento->sede->nombre ?? 'Sin asignar' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Área:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $elemento->area->nombre ?? 'Sin asignar' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Proveedor:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $elemento->proveedor ?: 'No especificado' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Soporte:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $elemento->soporte ?: 'No especificado' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-sm font-medium text-gray-500">Vencimiento de Garantía:</div>
                        <div class="col-span-2 text-sm text-gray-900">
                            {{ $elemento->vencimiento_garantia ?: 'No especificado' }}
                        </div>
                    </div>

                    @if($elemento->notas)
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Notas:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                {!! nl2br($elemento->notas) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Características -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2m-1-4H9a2 2 0 00-2 2v1a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Características</h3>
                </div>

                <div class="space-y-3">
                    @forelse ($elemento->categoria->caracteristicas as $caracteristica)
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">{{ $caracteristica->nombre }}:</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                @if (!empty($elemento->findValor($caracteristica->id)->valor))
                                    {{ $elemento->findValor($caracteristica->id)->valor }}
                                @else
                                    <span class="text-gray-400 italic">Sin datos</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No hay características definidas para esta categoría.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Historial -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Entregas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Historial de Entregas</h3>
                </div>

                @if($elemento->entregas->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entrega</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Firma</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devolución</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($elemento->entregas as $entrega)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 text-xs text-gray-900">{{ $entrega->user->realname }}</td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ Carbon\Carbon::create($entrega->fecha_entrega)->format('d-m-Y') }}
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ $entrega->fecha_firma ? Carbon\Carbon::create($entrega->fecha_firma)->format('d-m-Y') : 'Sin registro' }}
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ $entrega->fecha_devolucion ? Carbon\Carbon::create($entrega->fecha_devolucion)->format('d-m-Y') : 'Sin registro' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No hay entregas registradas.</p>
                @endif
            </div>

            <!-- Modificaciones -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center mb-4">
                    <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V9a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">Historial de Modificaciones</h3>
                </div>

                @if($elemento->modificaciones->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modificación</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Nuevo</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($elemento->modificaciones as $modificacion)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-2 text-xs text-gray-900">{{ $modificacion->modificacion }}</td>
                                        <td class="px-3 py-2 text-xs text-gray-900">{{ $modificacion->valor_nuevo }}</td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ Carbon\Carbon::create($modificacion->created_at)->format('d-m-Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">No hay modificaciones registradas.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>