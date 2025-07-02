<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="grid grid-cols-10 py-4">
        <div class="col-span-2 titulo-index">
            Listado Usuarios
        </div>
        <div class="col-span-3 mb-2 pl-3 mr-3">
            <select type="text" wire:model.live="sede_id" class="input-full">
                <option value="0">Todas</option>
                @foreach ($sedes as $sede)
                <option value="{{$sede->id}}">{{$sede->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-3 mb-2 pl-3 mr-3">
            <input type="text" wire:model.live="search" class="input-full" placeholder="Buscar...">
        </div>
        <div class="col-span-2 text-center">
            @can('Usuarios/Usuarios/Crear')
            <a href="{{route('usuarios.users.create')}}" class="block w-full text-sm py-2 boton-celeste">Nuevo Usuario</a>
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
                    Legajo
                </th>
                <th class="th-index">
                    Username
                </th>
                <th class="th-index">
                    Nombre
                </th>
                <th class="th-index">
                    Visible
                </th>
                <th class="th-index">
                    Último Ingreso
                </th>
                <th class="th-index">
                    
                </th>
                <th class="th-index">
                    
                </th>
                @can('Usuarios/Usuarios/Eliminar')
                <th class="th-index">
                    
                </th>
                @endcan
                {{-- <th class="th-index">
                    
                </th> --}}
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
            <tr class="hover:bg-gray-300">
                <th class="td-index">
                    {{$user->id}}
                </th>
                <th class="td-index">
                    {{$user->legajo}}
                </th>
                <td class="td-index ">
                    {{$user->name}}
                </td>
                <td class="td-index">
                    {{$user->realname}}
                </td>
                <td class="td-index">
                    @if ($user->visible)
                        Si
                    @else
                        No
                    @endif
                </td>
                <td class="td-index">
                    {{ $user->LastAccess ? \Carbon\Carbon::create($user->LastAccess->created_at)->format('d-m-Y H:i') : 'Nunca' }}
                </td>
                <td class="td-index">
                    @livewire('usuarios.users.index.reset-password-modal', ['user' => $user], key($user->id.microtime(true)))
                </td>
                <td class="td-index">
                    <a href="{{ route('usuarios.users.show', $user) }}" class="link-azul">Ver</a>
                    {{-- <a href="{{ route('users.show', $user) }}" class="link-azul ml-5">Eliminar</a> --}}
                </td>
                @can('Usuarios/Usuarios/Eliminar')
                <td class="td-index">
                    @livewire('usuarios.users.index.eliminar-modal', ['user' => $user], key($user->id.microtime(true)))
                </td>
                @endcan
                {{-- <td class="td-index">
                    <a href="{{ route('users.roles', $user) }}" class="link-azul">Editar Roles</a>
                </td> --}}
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" class="px-4 py-2">
                    {{ $users->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
