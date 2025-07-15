<x-app-layout>
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-10 py-4">
                    <div class="col-span-8 titulo-index">
                        Listado Roles
                    </div>
                    <div class="col-span-2 text-center">
                        @can('Usuarios/Roles/Crear')
                        <a href="{{route('usuarios.roles.create')}}" class="block w-full text-sm py-2 boton-celeste">Nuevo Rol</a>
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
                            <th class="th-index">
                                
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $sistema = '';
                        @endphp
                        @foreach ($roles as $role)
                        @php
                            $actual = explode('/', $role->name)[0];
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
                                {{$role->id}}
                            </td>
                            <td class="td-index">
                                {{$role->name}}
                            </td>
                            <td class="td-index">
                                @can('Usuarios/Roles/Editar')
                                <a href="{{ route('usuarios.roles.edit', $role) }}" class="link-azul">Editar Rol</a>
                                @endcan
                            </td>
                            <td class="td-index">
                                @can('Usuarios/Roles/Editar')
                                <a href="{{ route('usuarios.roles.permissions', $role) }}" class="link-azul">Editar Permisos</a>
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
