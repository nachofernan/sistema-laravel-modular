<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="grid grid-cols-10 py-4">
        <div class="col-span-6 titulo-index">
            Listado Usuarios
        </div>
        <div class="col-span-4 mb-2 pl-3 mr-3">
            <input type="text" wire:model.live="search"
                class="mt-1 input-full"
                placeholder="Buscar por nombre...">
        </div>
    </div>
    <table class="table-index">
        <thead>
            <tr>
                <th class="th-index">
                    Legajo
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
            @foreach ($users as $user)
                <tr class="hover:bg-gray-300">
                    <td class="td-index">
                        {{ $user->legajo }}
                    </td>
                    <td class="td-index">
                        {{ $user->realname }} ({{ $user->name }})
                    </td>
                    <td class="td-index">
                        {{ $user->elementos()->count() }}
                    </td>
                    <td class="td-index">
                        @can('Inventario/Usuarios/Ver')
                        <a href="{{ route('inventario.users.show', $user) }}" class="link-azul">Ver Elementos del Usuario</a>
                        @endcan
                    </td>
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
