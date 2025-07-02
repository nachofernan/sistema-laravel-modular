<div>
    {{-- Be like water. --}}
    <div class="grid grid-cols-10 py-2">
        <div class="col-span-8 titulo-index">
            Listado Proveedores
        </div>
        <div class="col-span-2 text-center">
            @can('Proveedores/Proveedores/Crear')
                <a href="{{ route('proveedores.proveedors.create') }}" class="block w-full mt-2 boton-celeste">Nuevo Proveedor</a>
            @endcan
        </div>
    </div>
    <div class="grid grid-cols-10 gap-5 pb-2">
        <div class="col-span-6">
            <input wire:model.live="search" type="text" placeholder="Buscar Proveedor por Razón Social, CUIT, Rubro o Subrubro"
                class="input-full">
        </div>
        <div class="col-span-2">
            <select wire:model.live="vencimiento" class="input-full">
                <option value="0">Sin filtrar vencimientos</option>
                <option value="1">Sin vencimientos cerca</option>
                <option value="2">Próximos a vencer</option>
                <option value="3">Con Documentación Vencida</option>
            </select>
        </div>
        <div class="col-span-2">
            <select wire:model.live="show" class="input-full">
                <option value="0">Todos los niveles</option>
                <option value="1">Nivel 1</option>
                <option value="2">Nivel 2</option>
                <option value="3">Nivel 3</option>
            </select>
        </div>
    </div>
    <div class="grid grid-cols-10 gap-5 pb-2">
        <div class="col-span-4">
            <select wire:model.live="rubro" class="input-full">
                <option value="0">Todos Los Rubros</option>
                @foreach ($rubros as $rubro)
                    <option value="{{ $rubro->id }}">{{ $rubro->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-4">
            <select wire:model.live="subrubro" class="input-full">
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
        <div class="col-span-2 flex items-center justify-center">
            @livewire('proveedores.proveedors.index.rubro-search-modal')
        </div>
    </div>
    
    @if ($proveedors->count())
        <table class="table-index">
            <thead class="bg-gray-50 px-5">
                <tr>
                    <th class="th-index">
                        ID
                    </th>
                    <th class="th-index">
                        CUIT
                    </th>
                    <th class="th-index">

                    </th>
                    <th class="th-index">
                        Razón Social
                    </th>
                    <th class="th-index">
                        Correo Electrónico
                    </th>
                    <th class="th-index">
                        Documentos
                    </th>
                    <th class="th-index">
                        Estado
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($proveedors as $proveedor)
                    <tr class="hover:bg-gray-300">
                        <td class="td-index">{{ $proveedor->id }}</td>
                        <td class="td-index">{{ $proveedor->cuit }}</td>
                        <td class="td-index">
                            @if ($proveedor->falta_a_vencimiento() < 0)
                                <span class="text-red-400 bg-red-400 px-1 text-xs rounded-full"
                                    title="Documentación Vencida">X</span>
                            @elseif ($proveedor->falta_a_vencimiento() < 30)
                                <span class="text-yellow-400 bg-yellow-400 px-1 text-xs rounded-full"
                                    title="Documentación a Vencer">X</span>
                            @endif
                        </td>
                        <td class="td-index">
                            <a href="./proveedors/{{ $proveedor->id }}" class="link-azul">
                                {{ $proveedor->razonsocial }}
                            </a>
                            @if ($proveedor->litigio)
                                <span class="text-xs px-1 bg-red-700 font-bold text-white rounded ml-2">Litigio</span>
                            @endif
                        </td>
                        <td class="td-index">
                            <a href="mailto:{{ $proveedor->correo }}" target="_blank"
                                class="link-azul">{{ $proveedor->correo }}</a>
                        </td>
                        <td class="td-index whitespace-nowrap">
                            @foreach ($proveedor->codigos_documentos as $codigo)
                                <span class="text-xs uppercase font-bold px-2 py-1 rounded text-gray-100 mr-1"
                                    title="{{ $codigo->nombre }}"
                                    style="background-color:
                                @php echo '#'.substr(md5($codigo->codigo), 0, 6); @endphp
                                ">{{ $codigo->codigo }}</span>
                            @endforeach
                        </td>
                        <td class="td-index">
                            {{ $proveedor->estado->estado }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="w-full font-bold text-center text-lg pt-5">
            No existen Proveedores con los criterios de búsqueda
        </div>
    @endif
    <div class="px-5 pt-5">
        {{ $proveedors->links() }}
    </div>
</div>
