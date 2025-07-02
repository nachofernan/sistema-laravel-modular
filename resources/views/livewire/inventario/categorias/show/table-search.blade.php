<div>
    {{-- Be like water. --}}
    <div class="subtitulo-show">
        Listado de elementos:
    </div>


    <div class="w-full grid grid-flow-col auto-cols-auto pb-2">
        <div class="col pt-2">Estados:</div>
        @foreach ($estados as $estado)
            <div class="col pt-2">
                {{ $estado->nombre }}:
                <input type="checkbox" wire:click.live="estado_update({{ $estado->id }})"
                    {{ in_array($estado->id, $estado_search) ? 'checked' : '' }}>
            </div>
        @endforeach
        <div class="col">
            <input type="text" wire:model.live="search"
                class="input-full" placeholder="Buscar por código...">
        </div>
    </div>

    <table class="table-index">
        <thead>
            <tr>
                <th class="th-index">
                    Código
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
                        {{ $elemento->estado->nombre }}
                    </td>
                    <td class="td-index">
                        {{ $elemento->user ? $elemento->user->realname : 'Sin Asignar' }}
                    </td>
                    <td class="td-index">
                        @can('Inventario/Inventario/Ver')
                        <a href="{{ route('inventario.elementos.show', $elemento) }}" class="text-blue-600 hover:underline">Ver
                            Elemento</a>
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
