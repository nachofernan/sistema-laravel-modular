<div>
    {{-- Be like water. --}}
    <div class="grid grid-cols-10 gap-4 py-2">
        <div class="col-span-4 titulo-index">
            Listado Capacitaciones
        </div>
        <div class="col-span-4">
            <input wire:model.live="search" type="text" placeholder="Buscar Capacitación"
                class="input-full">
        </div>
        <div class="col-span-2 text-center">
            @can('Capacitaciones/Capacitaciones/Crear')
                <a href="{{ route('capacitaciones.capacitacions.create') }}" class="block w-full boton-celeste">Nueva Capacitación</a>
            @endcan
        </div>
    </div>
    @if ($capacitacions->count())
        <table class="table-index">
            <thead class="bg-gray-50 px-5">
                <tr>
                    <th class="th-index">
                        ID
                    </th>
                    <th class="th-index">
                        Nombre
                    </th>
                    <th class="th-index">
                        Fecha
                    </th>
                    <th class="th-index">
                        Invitados
                    </th>
                    <th class="th-index">
                        Ingresar
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($capacitacions as $capacitacion)
                    <tr class="hover:bg-gray-300">
                        <td class="td-index">{{ $capacitacion->id }}</td>
                        <td class="td-index">{{ $capacitacion->nombre }}</td>
                        <td class="td-index">
                            {{ Carbon\Carbon::create($capacitacion->fecha)->format('d-m-Y') }}
                        </td>
                        <td class="td-index">{{ $capacitacion->invitaciones->count() }}</td>
                        <td class="td-index">
                            <a href="{{route('capacitaciones.capacitacions.show', $capacitacion)}}" class="link-azul">Ingresar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="w-full font-bold text-center text-lg pt-5">
            No existen Capacitaciones con los criterios de búsqueda
        </div>
    @endif
    <div class="px-5 pt-5">
        {{-- {{ $capacitacions->links() }} --}}
    </div>
</div>
