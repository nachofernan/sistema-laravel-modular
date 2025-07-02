<div wire:poll.3s="refreshTickets({{$lastId}})">
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="grid grid-cols-10 pt-4">
        <div class="col-span-7 titulo-index">
            Listado tickets
        </div>
        <div class="col-span-3 mb-2 pl-3">
            <input type="text" wire:model.live="search"
                class="input-full"
                placeholder="Buscar por código...">
        </div>
        {{-- <div class="col-span-2 text-center">
            <a href="{{ route('tickets.create') }}"
                class="block text-sm w-full boton-celeste">Nuevo ticket</a>
        </div> --}}
    </div>
    <div class="w-full text-center pb-3 grid grid-cols-10">
        <div class="col-span-5 grid grid-flow-col auto-cols-auto pt-2">
            <div class="col">Estados:</div>
            @foreach ($estados as $estado)
            <div class="col">
                {{$estado->nombre}}:
                <input type="checkbox" wire:click="estado_update({{$estado->id}})" {{in_array($estado->id, $estado_search) ? 'checked' : ''}}>
            </div>
            @endforeach
        </div>
        <div class="col-span-5 grid grid-cols-10">
            <div class="col-span-2  pt-2">Categoria:</div>
            <div class="col-span-8">
                <select wire:model.live="categoria"
                    class="input-full">
                    <option value="0">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
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
                    Ingresado
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
                    Encargado
                </th>
                <th class="th-index">

                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($tickets as $ticket)
                <tr class="hover:bg-gray-300">
                    <td class="td-index">
                        {{ $ticket->codigo }}
                    </td>
                    <td class="td-index">
                        {{ Carbon\Carbon::create($ticket->created_at)->format('d-m-Y H:i') }}
                    </td>
                    <td class="td-index">
                        {{ $ticket->categoria->nombre }}
                    </td>
                    <td class="td-index">
                        {{ $ticket->estado->nombre }}
                    </td>
                    <td class="td-index">
                        {{$ticket->user->realname}}
                    </td>
                    <td class="td-index">
                        {{ $ticket->encargado ? $ticket->encargado->realname : 'Sin Asignar' }}
                    </td>
                    <td class="td-index">
                        @can('Tickets/Tickets/Ver')
                        <a href="{{ route('tickets.tickets.show', $ticket) }}" class="link-azul">Ver ticket</a>
                            @can('Tickets/Tickets/Mensajes')
                            @if ($ticket->mensajesNuevos())
                                <span class="ml-2 font-bold bg-red-800 text-white text-xs rounded px-2 py-0">!</span>
                            @endif
                            @endcan
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" class="px-4 py-2">
                    {{ $tickets->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>