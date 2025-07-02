<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-2">
                    <div class="col-span-7 titulo-index">
                        Listado de Tipos de Documentos
                    </div>
                    <div class="col-span-3 text-center">
                        @can('Proveedores/DocumentoTipos/Crear')
                            <a href="{{ route('proveedores.documento_tipos.create') }}" class="block w-full mt-2 boton-celeste">Nuevo Tipo de Documento</a>
                        @endcan
                    </div>
                </div>

                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index">
                                Código
                            </th>
                            <th class="th-index">
                                Nombre
                            </th>
                            <th class="th-index">
                                Vencimiento
                            </th>
                            <th class="th-index">
                                Acceso
                            </th>
                        </tr>
                    </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($documentoTipos as $documentoTipo)
                <tr class="hover:bg-gray-200">
                    <td class="td-index">
                        <span class="text-xs uppercase font-bold px-2 py-1 rounded text-gray-100 mr-1" style="background-color:
                            @php
                                echo '#'.substr(md5($documentoTipo->codigo), 0, 6);
                            @endphp
                            ">
                            {{$documentoTipo->codigo}}
                        </span>
                    </td>
                    <td class="td-index">{{$documentoTipo->nombre}}</td>
                    <td class="td-index">
                        @if ($documentoTipo->vencimiento)
                            Si
                        @else
                            No
                        @endif
                    </td>
                    <td class="td-index">
                        <a href="{{route('proveedores.documento_tipos.show', $documentoTipo)}}" class="link-azul">Acceder al Modelo</a>
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>