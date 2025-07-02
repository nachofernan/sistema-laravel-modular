<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Permisos
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Usuarios/Permisos/Crear')
                        <a href="{{route('usuarios.permissions.create')}}" class="block w-full text-sm py-2 boton-celeste">Nuevo Permiso</a>
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
                                
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                        $sistema = '';
                        @endphp
                        @foreach ($permissions as $permission)
                        @php
                        $actual = explode('/', $permission->name)[0];
                        @endphp
                        @if ($actual != $sistema)
                            @php
                                $sistema = $actual;
                            @endphp
                            <tr class="hover:bg-gray-300">
                                <td colspan="4" class="td-index">
                                    {{$sistema}}
                                </td>
                            </tr>
                        @endif
                        <tr class="hover:bg-gray-300">
                            <td class="td-index">
                                {{$permission->id}}
                            </td>
                            <td class="td-index">
                                {{$permission->name}}
                            </td>
                            <td class="td-index">
                                @can('Usuarios/Permisos/Editar')
                                <a href="{{ route('usuarios.permissions.edit', $permission) }}" class="link-azul">Editar Permiso</a>
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
