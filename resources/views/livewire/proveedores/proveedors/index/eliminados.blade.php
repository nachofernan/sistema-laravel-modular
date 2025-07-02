<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="grid grid-cols-10 py-2">
        <div class="col-span-10 titulo-index">
            Listado Proveedores Eliminados
        </div>
        <div class="col-span-10">
            <input wire:model.live="search" type="text" placeholder="Buscar Proveedor por Razón Social o CUIT"
                class="input-full">
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
                            @else
                                @if ($proveedor->falta_a_vencimiento() < 30)
                                    <span class="text-yellow-400 bg-yellow-400 px-1 text-xs rounded-full"
                                        title="Documentación a Vencer">X</span>
                                @endif
                            @endif
                        </td>
                        <td class="td-index">
                            <a href="./proveedors/{{ $proveedor->id }}" class="link-azul">
                                {{ $proveedor->razonsocial }}
                            </a>
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
                                @php
echo '#'.substr(md5($codigo->codigo), 0, 6); @endphp
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
