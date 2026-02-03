<x-app-layout>
    <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-900">Documentos Pendientes de Validación</h1>
    </div>
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            
            <!-- Tabla -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Proveedor
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Documento
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Estado / Vencimiento
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    Fecha Carga
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($validaciones as $validacion)
                        @php
                            $tieneVencimiento = $validacion->documento->tieneVencimiento();
                            if($tieneVencimiento) {
                                $vencimientoRaw = $validacion->documento->getRawOriginal('vencimiento');
                                $anio = (int) substr($vencimientoRaw, 0, 4);
                            } 
                            $diasRestantes = ($tieneVencimiento && $anio < 2035) ? now()->diffInDays($validacion->documento->vencimiento, false) : null;
                            $estadoVencimiento = $diasRestantes !== null ? 
                                ($diasRestantes < 0 ? 'vencido' : ($diasRestantes <= 30 ? 'proximo' : 'vigente')) : 
                                'sin_vencimiento';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150" 
                            x-data="{ expanded: false }"
                            :class="{ 'bg-red-50': '{{ $estadoVencimiento }}' === 'vencido' }">
                            
                            <!-- Proveedor -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 max-w-xs break-words">
                                    <a href="{{route('proveedores.proveedors.show', $validacion->documento->documentable_type == 'App\Models\Proveedores\Proveedor' ? $validacion->documento->documentable : $validacion->documento->documentable->proveedor)}}" 
                                       class="link-azul hover:text-blue-700 transition-colors">
                                        {{$validacion->documento->documentable_type == 'App\Models\Proveedores\Proveedor' ? 
                                          $validacion->documento->documentable->razonsocial : 
                                          $validacion->documento->documentable->proveedor->razonsocial}}
                                    </a>
                                </div>
                            </td>

                            <!-- Documento -->
                            <td class="px-6 py-4">
                                <a href="{{ route('proveedores.documentos.show', $validacion->documento) }}" 
                                    class="link-azul hover:text-blue-700 text-sm transition-colors max-w-xs break-words block" 
                                    target="_blank"
                                    x-tooltip="Ver documento">
                                        {{$validacion->documento->documentoTipo ? 
                                            $validacion->documento->documentoTipo->nombre : 
                                            ($validacion->documento->documentable->tipo == 'representante' ? 'Representante Legal' : 'Apoderado')}}
                                 </a>
                            </td>

                            <!-- Estado/Vencimiento -->
                            <td class="px-6 py-4">
                                @if ($tieneVencimiento)
                                    <div class="flex items-center">
                                        @if ($estadoVencimiento == 'vencido')
                                            <div class="flex items-center text-red-600">
                                                <div>
                                                    <div class="text-xs">{{$validacion->documento->vencimiento->format('d/m/Y')}}</div>
                                                </div>
                                            </div>
                                        @elseif ($estadoVencimiento == 'proximo')
                                            <div class="flex items-center text-yellow-600">
                                                <div>
                                                    <div class="text-xs">{{$validacion->documento->vencimiento->format('d/m/Y')}}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center text-green-600">
                                                <div>
                                                    <div class="text-xs">{{$validacion->documento->vencimiento->format('d/m/Y')}}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex items-center text-gray-500">
                                        <span class="text-sm">Sin vencimiento</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Fecha Carga -->
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{$validacion->documento->created_at->format('d/m/Y')}}
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">                                   
                                    @livewire('proveedores.validacions.validar-modal', ['validacion' => $validacion], key($validacion->id))
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay documentos pendientes</h3>
                                    <p class="text-gray-500">Todos los documentos han sido validados correctamente.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alpine.js para tooltips -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.directive('tooltip', (el, { expression }) => {
                el.title = expression;
            })
        })
    </script>
</x-app-layout>