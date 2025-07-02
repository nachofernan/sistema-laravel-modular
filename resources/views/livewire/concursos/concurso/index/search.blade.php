<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="grid grid-cols-10 pt-4 gap-3">
        <div class="col-span-5 titulo-index">
            Listado de Concursos
        </div>
        <div class="col-span-5 mb-2 pl-3">
            <input type="text" wire:model.live="search"
                class="input-full"
                placeholder="Buscar por nombre...">
        </div>
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
    </div>
    <table class="table-index">
        <thead>
            <tr>
                <th class="th-index">
                    Nombre
                </th>
                <th class="th-index">
                    Fecha Inicio
                </th>
                <th class="th-index">
                    Fecha Cierre
                </th>
                <th class="th-index">
                    Estado
                </th>
                <th class="th-index">
                    Rubro/Subrubro
                </th>
                <th class="th-index">
                    Proveedores
                </th>
                <th class="th-index">

                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($concursos as $concurso)
                <tr class="hover:bg-gray-300">
                    <td class="td-index">
                        {{ $concurso->nombre }}
                    </td>
                    <td class="td-index">
                        {{ $concurso->fecha_inicio->format('d-m-Y - H:i') }}
                    </td>
                    <td class="td-index">
                        {{ $concurso->fecha_cierre->format('d-m-Y - H:i') }}
                    </td>
                    <td class="td-index">
                        {{ $concurso->estado->nombre }}
                    </td>
                    <td class="td-index">
                        @if ($concurso->subrubro)
                            {{$concurso->subrubro->nombre}}
                        @else
                            Sin Rubro/Subrubro
                        @endif
                    </td>
                    <td class="td-index">
                        {{ count($concurso->invitaciones) }}
                    </td>
                    <td class="td-index">
                        @can('Concursos/Concursos/Ver')
                        <a href="{{ route('concursos.concursos.show', $concurso) }}" class="link-azul">Ver concurso</a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" class="px-4 py-2">
                    {{ $concursos->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>