<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-orange-50 to-amber-50 border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Mi Inventario</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                {{ $elementos->count() }} elementos
            </span>
        </div>
    </div>

    @if($elementos->count() > 0)
        <!-- Tabla de Inventario -->
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Elemento
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Código
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha de Entrega
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($elementos as $elemento)
                    @php
                        $entrega = $elemento->entregaActual();
                        $requiereFirma = $entrega && 
                                        $entrega->user_id === Auth::id() && 
                                        !$entrega->fecha_devolucion && 
                                        !$entrega->fecha_firma;
                        $yaFirmado = $entrega && $entrega->fecha_firma;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Elemento -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                        @if($elemento->categoria->icono)
                                            {!! $elemento->categoria->icono !!}
                                        @else
                                            <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $elemento->categoria->nombre }}
                                    </div>
                                    @if($elemento->descripcion)
                                    <div class="text-xs text-gray-500">
                                        {{ Str::limit($elemento->descripcion, 30) }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Código -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-600 text-white">
                                {{ $elemento->codigo }}
                            </span>
                        </td>

                        <!-- Fecha de Entrega -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($entrega)
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('H:i') }}
                                </div>
                            @else
                                <span class="text-sm text-gray-400">Sin entrega</span>
                            @endif
                        </td>

                        <!-- Estado/Firma -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($requiereFirma)
                                <button wire:click="firmarEntrega({{ $elemento->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition-colors">
                                    <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Firmar Entrega
                                </button>
                            @elseif($yaFirmado)
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-1">
                                        <svg class="h-1.5 w-1.5 mr-1.5 fill-current text-green-400" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        Firmado
                                    </span>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($entrega->fecha_firma)->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Estado vacío -->
        <div class="px-6 py-12 text-center">
            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes elementos asignados</h3>
            <p class="text-gray-500">Los elementos del inventario que te sean asignados aparecerán aquí.</p>
        </div>
    @endif
</div>