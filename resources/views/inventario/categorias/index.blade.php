<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-10 titulo-index">
                        Listado Categorías
                    </div>
                </div>
                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index">
                                Ícono
                            </th>
                            <th class="th-index">
                                Prefijo
                            </th>
                            <th class="th-index">
                                Nombre
                            </th>
                            <th class="th-index">
                                Elementos
                            </th>
                            <th class="th-index">

                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr class="hover:bg-gray-300">
                                <td class="td-index">
                                    @if ($categoria->icono)
                                        {!! $categoria->icono !!}
                                        <!-- https://www.svgrepo.com/svg/245757/video-call, editar vector, copiar y pegar en la BD. Además agregarle un height="50" a la etiqueta svg -->
                                    @endif
                                </td>
                                <td class="td-index">
                                    {{ $categoria->prefijo }}
                                </td>
                                <td class="td-index">
                                    {{ $categoria->nombre }}
                                </td>
                                <td class="td-index">
                                    {{ count($categoria->elementos) }}
                                </td>
                                <td class="td-index">
                                    @can('Inventario/Categorias/Ver')
                                    <a href="{{ route('inventario.categorias.show', $categoria) }}" class="link-azul">Ver
                                        Categoría</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
