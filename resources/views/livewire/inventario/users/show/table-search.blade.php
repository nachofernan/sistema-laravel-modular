<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
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
    </div>

    <table class="table-index">
        <thead>
            <tr>
                <th class="th-index">
                    Ícono
                </th>
                <th class="th-index">
                    Código
                </th>
                <th class="th-index">
                    Estado
                </th>
                <th class="th-index">
                    Categoria
                </th>
                <th class="th-index">

                </th>
                <th class="th-index">

                </th>
            </tr>
        </thead>

        <tbody>
            @foreach ($elementos as $elemento)
                <tr class="hover:bg-gray-300">
                    <td class="td-index">
                        @if ($elemento->categoria->icono)
                            {!! $elemento->categoria->icono !!}
                            <!-- https://www.svgrepo.com/svg/245757/video-call, editar vector, copiar y pegar en la BD. Además agregarle un height="50" a la etiqueta svg -->
                        @endif
                    </td>
                    <td class="td-index">
                        {{ $elemento->codigo }}
                    </td>
                    <td class="td-index">
                        {{ $elemento->estado->nombre }}
                    </td>
                    <td class="td-index">
                        {{ $elemento->categoria->nombre }}
                    </td>
                    <td class="td-index">
                        @if (
                            $elemento->entregaActual() &&
                                !$elemento->entregaActual()->fecha_devolucion)
                            @if ($elemento->entregaActual()->fecha_firma)
                                    Firmado:
                                    {{ Carbon\Carbon::create($elemento->entregaActual()->fecha_firma)->format('d-m-Y') }}
                            @else
                                Aún no firmado
                            @endif
                        @else
                            Firma no solicitada
                        @endif
                    </td>
                    <td class="td-index">
                        @can('Inventario/Elementos/Ver')
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
