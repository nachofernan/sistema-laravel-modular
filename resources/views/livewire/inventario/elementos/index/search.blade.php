<div>
    {{-- Be like water. --}}
    <div class="grid grid-cols-10 py-4">
        <div class="col-span-4 titulo-index">
            Listado Elementos
        </div>
        <div class="col-span-4 mb-2 pl-3 mr-3">
            <input type="text" wire:model.live="search"
                class="input-full"
                placeholder="Buscar por código...">
        </div>
        <div class="col-span-2 text-center">
            @can('Inventario/Elementos/Crear')
            <a href="{{ route('inventario.elementos.create') }}"
                class="block w-full text-sm boton-celeste">Nuevo Elemento</a>
            @endcan
        </div>
    </div>
    <div class="w-full grid grid-cols-4 text-center pb-3">
        <div class="col-span-2 grid grid-flow-col auto-cols-auto pt-1">
            <div class="col">Estados:</div>
            @foreach ($estados as $estado)
            <div class="col">
                {{$estado->nombre}}:
                <input type="checkbox" wire:click="estado_update({{$estado->id}})" {{in_array($estado->id, $estado_search) ? 'checked' : ''}}>
            </div>
            @endforeach
        </div>
        <div class="col text-right pt-1 mr-4">
            Categoría:
        </div>
        <div class="col">
            <div class="col">
                <select wire:model.live="categoria" class="input-full">
                    <option value="0">Todos</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <table class="table-index">
        <thead>
            <tr>
                <th class="th-index">
                    Código
                </th>
                <th class="th-index">
                    Categoría
                </th>
                <th class="th-index">
                    Estado
                </th>
                <th class="th-index">
                    Usuario
                </th>
                <th class="th-index">

                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($elementos as $elemento)
                <tr class="hover:bg-gray-300">
                    <td class="td-index">
                        {{ $elemento->codigo }}
                    </td>
                    <td class="td-index">
                        {{ $elemento->categoria->nombre }}
                    </td>
                    <td class="td-index">
                        {{ $elemento->estado->nombre }}
                    </td>
                    <td class="td-index">
                        {{ $elemento->user ? $elemento->user->realname : 'Sin Asignar' }}
                    </td>
                    <td class="td-index">
                        @can('Inventario/Elementos/Ver')
                        <a href="{{ route('inventario.elementos.show', $elemento) }}" class="link-azul">Ver Elemento</a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" class="px-4 py-2">
                    {{ $elementos->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
