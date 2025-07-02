<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Módulos
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Usuarios/Modulos/Crear')
                        <a href="{{route('usuarios.modulos.create')}}" class="block w-full text-sm py-2 boton-celeste">Nuevo Módulo</a>
                        @endcan
                    </div>
                </div>
                <table class="table-index">
                    <thead>
                        <tr>
                            <th class="th-index">
                                ID
                            </th>
                            <th class="th-index">
                                Nombre
                            </th>
                            <th class="th-index">
                                Descripción
                            </th>
                            <th class="th-index">
                                Estado
                            </th>
                            <th class="th-index">
                                
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($modulos as $modulo)
                        <tr class="hover:bg-gray-300">
                            <td class="td-index">
                                {{$modulo->id}}
                            </td>
                            <td class="td-index">
                                {{$modulo->nombre}}
                            </td>
                            <td class="td-index">
                                {{$modulo->descripcion}}
                            </td>
                            <td class="td-index">
                                {{ucfirst($modulo->estado)}}
                            </td>
                            <td class="td-index">
                                <a href="{{ route('usuarios.modulos.show', $modulo) }}" class="link-azul">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
