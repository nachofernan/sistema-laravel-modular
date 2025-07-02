<x-app-layout>
    <div class="w-full xl:w-12/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-12">
                    <div class="titulo-show">
                        {{ $categoria->nombre }} - ({{ $categoria->prefijo }})
                    </div>
                </div>
            </div>

            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-6 gap-4">
                    <div class="col-span-4">
                        @livewire('inventario.categorias.show.table-search', ['categoria' => $categoria], key($categoria->id . microtime(true)))
                    </div>
                    <div class="col-span-2">
                        <div class="pb-4">
                            <div class="subtitulo-show grid grid-cols-6">
                                <div class="col-span-5">
                                    Características: 
                                </div>
                                <div class="col-span-1">
                                    @can('Inventario/Categorias/Editar')
                                    @livewire('inventario.categorias.caracteristicas.create', ['categoria' => $categoria], key($categoria->id.microtime(true)))
                                    @endcan
                                </div>
                            </div>
                            <table class="table-index">
                                <thead>
                                    <th class="th-index">Nombre</th>
                                    <th class="th-index text-center">Opciones</th>
                                </thead>
                                <tbody>
                                    @foreach ($categoria->caracteristicas as $caracteristica)
                                    <tr>
                                        <td class="td-index">{{ $caracteristica->nombre }}</td>
                                        <td class="td-index text-center">
                                            @if ($caracteristica->con_opciones)
                                            @can('Inventario/Categorias/Editar')
                                            @livewire('inventario.categorias.caracteristicas.opciones', ['caracteristica' => $caracteristica], key($caracteristica->id.microtime(true)))
                                            @endcan
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
