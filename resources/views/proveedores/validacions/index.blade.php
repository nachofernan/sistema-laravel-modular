<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-2">
                    <div class="col-span-7 titulo-index">
                        Listado de Documentos sin Validar
                    </div>
                </div>

                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index">
                                Proveedor
                            </th>
                            <th class="th-index">
                                Documento
                            </th>
                            <th class="th-index">
                                Fecha de Vencimiento
                            </th>
                            <th class="th-index">
                                Fecha Carga
                            </th>
                            <th class="th-index">
                                Ver Documento
                            </th>
                            <th class="th-index">
                                Acciones
                            </th>
                        </tr>
                    </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($validaciones as $validacion)
                <tr class="hover:bg-gray-200">
                    <td class="td-index">
                        <a href="{{route('proveedores.proveedors.show', $validacion->documento->documentable_type == 'App\Models\Proveedores\Proveedor' ? $validacion->documento->documentable : $validacion->documento->documentable->proveedor)}}" class="link-azul">
                            {{$validacion->documento->documentable_type == 'App\Models\Proveedores\Proveedor' ? $validacion->documento->documentable->razonsocial : $validacion->documento->documentable->proveedor->razonsocial}}
                        </a>
                    </td>
                    <td class="td-index">
                        <span class="text-xs uppercase font-bold">
                            {{$validacion->documento->documentoTipo ? 
                                    $validacion->documento->documentoTipo->nombre : 
                                    ($validacion->documento->documentable->tipo == 'representante' ? 'Representante Legal' : 'Apoderado')}}
                        </span>
                    </td>
                    <td class="td-index">
                        @if ($validacion->documento->tieneVencimiento())
                            {{$validacion->documento->vencimiento->format('d-m-Y')}}
                        @else
                        Sin Vencimiento
                        @endif
                    </td>
                    <td class="td-index">
                        {{$validacion->documento->created_at->format('d-m-Y')}}
                    </td>
                    <td class="td-index">
                        <a href="{{Storage::disk('proveedores')->url($validacion->documento->file_storage)}}" class="link-azul" target="_blank">
                            Descargar
                        </a>
                    </td>
                    <td class="td-index">
                        @livewire('proveedores.validacions.validar-modal', ['validacion' => $validacion], key($validacion->id))
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>