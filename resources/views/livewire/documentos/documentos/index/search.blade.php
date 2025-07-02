<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="grid grid-cols-10 py-4">
        <div class="col-span-8 titulo-index">
            Listado Documentos
        </div>
        <div class="col-span-2 text-center">
            @can('Documentos/Documentos/Crear')
            <a href="{{route('documentos.documentos.create')}}" class="block w-full mt-2 boton-celeste">Nuevo Documento</a>
            @endcan
        </div>
    </div>
    @foreach ($categorias as $categoria)
        <div>
            {{$categoria->nombre}}
        </div>
        <table class="table-index">
            <thead>
                <tr>
                    <th class="th-index">
                        Orden
                    </th>
                    <th class="th-index">
                        Visibilidad
                    </th>
                    <th class="th-index">
                        Documento
                    </th>
                    <th class="th-index">
                        Descripción
                    </th>
                    <th class="th-index">
                        Descargas
                    </th>
                    <th class="th-index">
                        Archivo
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($categoria->documentos->sortBy('orden') as $documento)
                <tr class="hover:bg-gray-300">
                    <th class="td-index">
                        {{$documento->orden}}
                    </th>
                    <th class="td-index">
                        {{$documento->visible ? 'Si' : 'No'}}
                    </th>
                    <td class="td-index">
                        @can('Documentos/Documentos/Ver')
                        <a href="{{ route('documentos.documentos.show', $documento) }}" class="link-azul">
                        @endcan
                        {{$documento->nombre}}
                        @can('Documentos/Documentos/Ver')
                        </a>
                        @endcan

                    </td>
                    <td class="td-index">
                        {{$documento->descripcion}}
                    </td>
                    <td class="td-index">
                        {{$documento->descargas->count()}}
                    </td>
                    <td class="td-index">
                        {{-- <a href="{{Storage::disk('public')->url('../public/storage/'.$documento->file_storage)}}" target="_blank">Descargar</a> -  --}}
                        <a href="{{route('documentos.documentos.download', $documento)}}" target="_blank" class="link-azul">Descargar</a>
                        {{-- {{$documento->archivo}} --}}
                    </td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
    @endforeach
</div>
