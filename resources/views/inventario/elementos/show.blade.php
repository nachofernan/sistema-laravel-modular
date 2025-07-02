<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        {{ $elemento->codigo }}
                    </div>
                    <div class="text-xs">
                        <a href="{{ route('inventario.categorias.show', $elemento->categoria) }}"
                            class="link-azul">{{ $elemento->categoria->nombre }}</a>
                        - {{ $elemento->estado->nombre }}
                    </div>
                </div>
                <div class="col-span-4 text-right pt-3 text-sm">
                    @can('Inventario/Elementos/Editar')
                    <a href="{{ route('inventario.elementos.edit', $elemento) }}" class="boton-celeste">Editar</a>
                    @endcan
                </div>
            </div>
            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-2 gap-5">
                    <div class="col">
                        <div class="grid-datos-show">
                            <div class="atributo-show">
                                Cargado
                            </div>
                            <div class="valor-show">
                                {{ Carbon\Carbon::create($elemento->created_at)->format('d-m-Y') }}
                            </div>
                            <div class="atributo-show">
                                Usuario
                            </div>
                            <div class="valor-show">
                                {{ $elemento->user->realname ?? '' }}
                                @if ($elemento->user)
                                    @livewire('inventario.elementos.show.button-firma', ['elemento' => $elemento], key($elemento->id . microtime(true)))
                                @endif
                            </div>
                            <div class="atributo-show">
                                Sede
                            </div>
                            <div class="valor-show">
                                {{ $elemento->sede->nombre ?? '' }}
                            </div>
                            <div class="atributo-show">
                                Área
                            </div>
                            <div class="valor-show">
                                {{ $elemento->area->nombre ?? '' }}
                            </div>
                            <div class="atributo-show">
                                Proveedor
                            </div>
                            <div class="valor-show">
                                {{ $elemento->proveedor }}
                            </div>
                            <div class="atributo-show">
                                Soporte
                            </div>
                            <div class="valor-show">
                                {{ $elemento->soporte }}
                            </div>
                            <div class="atributo-show">
                                Vencimiento de Garantía
                            </div>
                            <div class="valor-show">
                                {{ $elemento->vencimiento_garantia }}
                            </div>
                            <div class="atributo-show">
                                Notas
                            </div>
                            <div class="valor-show">
                                {!! nl2br($elemento->notas) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="grid-datos-show pb-4">
                            @foreach ($elemento->categoria->caracteristicas as $caracteristica)
                                <div class="atributo-show">
                                    {{ $caracteristica->nombre }}
                                </div>
                                <div class="valor-show">
                                    {!! !empty($elemento->findValor($caracteristica->id)->valor)
                                        ? $elemento->findValor($caracteristica->id)->valor
                                        : html_entity_decode('<i class="text-xs">Sin Datos</i>') !!}
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="py-2">
                            Entregas
                        </div>
                        <table class="table-index">
                            <thead>
                                <tr>
                                    <th class="th-index">Usuario</th>
                                    <th class="th-index">Entrega</th>
                                    <th class="th-index">Firma</th>
                                    <th class="th-index">Devolución</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elemento->entregas as $entrega)
                                    <tr class="hover:bg-gray-300">
                                        <td class="td-index">{{ $entrega->user->realname }}</td>
                                        <td class="td-index">
                                            {{ Carbon\Carbon::create($entrega->fecha_entrega)->format('d-m-Y') }}</td>
                                        <td class="td-index">
                                            {{ $entrega->fecha_firma ? Carbon\Carbon::create($entrega->fecha_firma)->format('d-m-Y') : 'Sin registro' }}
                                        </td>
                                        <td class="td-index">
                                            {{ $entrega->fecha_devolucion ? Carbon\Carbon::create($entrega->fecha_devolucion)->format('d-m-Y') : 'Sin registro' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                        <div class="py-2">
                            Modificaciones
                        </div>
                        <table class="table-index">
                            <thead>
                                <tr>
                                    <th class="th-index">Modificación</th>
                                    <th class="th-index">Valor Nuevo</th>
                                    <th class="th-index">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($elemento->modificaciones as $modificacion)
                                    <tr class="hover:bg-gray-300">
                                        <td class="td-index">{{ $modificacion->modificacion }}</td>
                                        <td class="td-index">{{ $modificacion->valor_nuevo }}</td>
                                        <td class="td-index">
                                            {{ Carbon\Carbon::create($modificacion->created_at)->format('d-m-Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
