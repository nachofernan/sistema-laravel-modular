<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Sedes
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Usuarios/Sedes/Crear')
                        <a href="{{route('usuarios.sedes.create')}}" class="block w-full text-sm py-2 boton-celeste">Nueva Sede</a>
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
                                Personal
                            </th>
                            <th class="th-index">
                                
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sedes as $sede)
                        <tr class="hover:bg-gray-300">
                            <td class="td-index">
                                {{$sede->id}}
                            </td>
                            <td class="td-index">
                                {{$sede->nombre}}
                            </td>
                            <td class="td-index">
                                {{count($sede->users)}}
                            </td>
                            <td class="td-index">
                                @can('Usuarios/Sedes/Editar')
                                <a href="{{ route('usuarios.sedes.edit', $sede) }}" class="link-azul">Editar Sede</a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
