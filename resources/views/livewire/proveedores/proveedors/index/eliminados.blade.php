<div class="p-4">

    {{-- Filtros compactos en 2 filas --}}
    <div class="space-y-2 mb-4">
        {{-- Primera fila: Búsqueda principal --}}
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Buscar por Razón Social, CUIT"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            
    </div>
    
    {{-- Tabla compacta --}}
    @if ($proveedors->count())
        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">ID</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">CUIT</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razón Social</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo Electrónico</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($proveedors as $proveedor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-2 text-xs text-gray-900 font-mono">{{ $proveedor->id }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900 font-mono">{{ $proveedor->cuit }}</td>
                            <td class="px-3 py-2">
                                <div class="flex items-center space-x-2">
                                    <a href="./proveedors/{{ $proveedor->id }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        {{ $proveedor->razonsocial }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <a href="mailto:{{ $proveedor->correo }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-xs break-all whitespace-nowrap">
                                    {{ $proveedor->correo }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <div class="text-lg font-medium">No existen Proveedores con los criterios de búsqueda</div>
        </div>
    @endif
    
    {{-- Paginación --}}
    @if ($proveedors->count())
        <div class="mt-4 flex justify-center">
            {{ $proveedors->links() }}
        </div>
    @endif
</div>