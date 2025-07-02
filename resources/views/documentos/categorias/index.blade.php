<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Categorías
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Documentos/Categorias/Crear')
                        <a href="{{route('documentos.categorias.create')}}" class="block w-full mt-2 boton-celeste">Nuevo Categoría</a>
                        @endcan
                    </div>
                </div>
                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index">
                                Categoría
                            </th>
                            <th class="th-index">
                                Documentos
                            </th>
                            <th class="th-index">
                                
                            </th>
                        </tr>
                    </thead>
            
                    <tbody>
                        @foreach ($categorias as $categoria)
                        <tr class="hover:bg-gray-300">
                            <td class="font-bold td-index">
                                {{$categoria->nombre}}
                            </td>
                            <td class="td-index">
                                
                            </td>
                            <td class="td-index">
                                @can('Documentos/Categorias/Editar')
                                <a href="{{ route('documentos.categorias.edit', $categoria) }}" class="link-azul">Editar</a>
                                @endcan
                            </td>
                        </tr>
                            @foreach ($categoria->hijos as $categoria_hijo)
                            <tr class="hover:bg-gray-300">
                                <td class="pl-10 td-index">
                                    {{$categoria_hijo->nombre}}
                                </td>
                                <td class="td-index">
                                    {{$categoria_hijo->documentos->count()}}
                                </td>
                                <td class="td-index">
                                    @can('Documentos/Categorias/Ver')
                                    <a href="{{ route('documentos.categorias.show', $categoria_hijo) }}" class="link-azul">Ver Documentos</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
            
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>