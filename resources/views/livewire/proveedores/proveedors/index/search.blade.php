<div class="p-4">
    {{-- Header compacto --}}
    

    {{-- Filtros compactos en 2 filas --}}
    <div class="space-y-2 mb-4">
        {{-- Primera fila: Búsqueda principal --}}
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-6">
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Buscar por Razón Social, CUIT, Rubro o Subrubro"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="col-span-3">
                <select wire:model.live="vencimiento" 
                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="0">Sin filtrar vencimientos</option>
                    <option value="1">Sin vencimientos cerca</option>
                    <option value="2">Próximos a vencer</option>
                    <option value="3">Con Documentación Vencida</option>
                </select>
            </div>
            <div class="col-span-3">
                <select wire:model.live="show" 
                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="0">Todos los niveles</option>
                    <option value="1">Nivel 1</option>
                    <option value="2">Nivel 2</option>
                    <option value="3">Nivel 3</option>
                </select>
            </div>
        </div>

        {{-- Segunda fila: Filtros por rubro y subrubro --}}
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-4">
                <select wire:model.live="rubro" 
                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="0">Todos Los Rubros</option>
                    @foreach ($rubros as $rubro)
                        <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-5">
                <select wire:model.live="subrubro" 
                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                    @if ($rubro_sel)
                        <option value="0">Todos Los Subrubros</option>
                        @foreach ($rubro_sel->subrubros as $subrubro)
                            <option value="{{ $subrubro->id }}">{{ $subrubro->nombre }}</option>
                        @endforeach
                    @else
                        <option value="0">Todos Los Subrubros</option>
                    @endif
                </select>
            </div>
            <div class="col-span-3 flex items-center justify-center">
                @livewire('proveedores.proveedors.index.rubro-search-modal')
            </div>
        </div>
    </div>
    
    {{-- Tabla compacta --}}
    @if ($proveedors->count())
        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">ID</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">CUIT</th>
                        <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-8"></th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razón Social</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden 2xl:table-cell">Correo Electrónico</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider table-cell 2xl:hidden"></th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Documentos</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($proveedors as $proveedor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-2 text-xs text-gray-900 font-mono">{{ $proveedor->id }}</td>
                            <td class="px-3 py-2 text-xs text-gray-900 font-mono">{{ $proveedor->cuit }}</td>
                            <td class="px-2 py-2 text-center">
                                @if ($proveedor->falta_a_vencimiento() < 0)
                                    <span class="inline-block w-2 h-2 bg-red-500 rounded-full" 
                                          title="Documentación Vencida"></span>
                                @elseif ($proveedor->falta_a_vencimiento() < 30)
                                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full" 
                                          title="Documentación a Vencer"></span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center space-x-2">
                                    <a href="./proveedors/{{ $proveedor->id }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                        {{ $proveedor->razonsocial }}
                                    </a>
                                    @if ($proveedor->litigio)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Litigio
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-2 hidden 2xl:table-cell">
                                <a href="mailto:{{ $proveedor->correo }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-xs break-all whitespace-nowrap">
                                    {{ $proveedor->correo }}
                                </a>
                            </td>
                            <td class="px-3 py-2 table-cell 2xl:hidden">
                                <a href="mailto:{{ $proveedor->correo }}" 
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 text-xs break-all whitespace-nowrap" 
                                   title="{{ $proveedor->correo }}">
                                   <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <div class="flex flex-wrap justify-center gap-1">
                                    @foreach ($proveedor->codigos_documentos as $codigo)
                                        <span class="inline-block text-xs font-semibold px-1.5 py-0.5 rounded text-white"
                                              title="{{ $codigo->nombre }}"
                                              style="background-color: #{{ substr(md5($codigo->codigo), 0, 6) }}">
                                            {{ $codigo->codigo }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $proveedor->estado->estado }}
                                </span>
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