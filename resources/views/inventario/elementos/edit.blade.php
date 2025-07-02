<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('inventario.elementos.update', $elemento) }}" method="POST">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            {{ $elemento->codigo }}
                        </div>
                        <div class="text-xs italic">
                            {{ $elemento->categoria->nombre }} - {{ $elemento->estado->nombre }}
                        </div>
                    </div>
                    <div class="col-span-4 text-right pt-3 text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('inventario.elementos.show', $elemento) }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Estado
                                </div>
                                <div class="valor-edit">
                                    <select name="estado_id" class="input-full">
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ $estado->id == $elemento->estado->id ? 'selected' : '' }}>
                                                {{ $estado->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Proveedor
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="proveedor" value="{{ $elemento->proveedor }}"
                                        class="input-full" placeholder="Proveedor">
                                </div>
                                <div class="atributo-edit">
                                    Soporte
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="soporte" value="{{ $elemento->soporte }}"
                                        class="input-full" placeholder="Soporte">
                                </div>
                                <div class="atributo-edit">
                                    Vencimiento de Garantía
                                </div>
                                <div class="valor-edit">
                                    <input type="date" name="vencimiento_garantia"
                                        value="{{ $elemento->vencimiento_garantia }}" class="input-full"
                                        placeholder="Vencimiento de Garantía">
                                </div>
                                <div class="atributo-edit">
                                    Usuario
                                </div>
                                <div class="valor-edit">
                                    <select name="user_id" class="input-full">
                                        <option value="">Ninguno</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ $user->id == $elemento->user_id ? 'selected' : '' }}>
                                                {{ $user->realname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Sede

                                </div>
                                <div class="valor-edit">
                                    <select name="sede_id" class="input-full">
                                        <option value="">Ninguno</option>
                                        @foreach ($sedes as $sede)
                                            <option value="{{ $sede->id }}"
                                                {{ $sede->id == $elemento->sede_id ? 'selected' : '' }}>
                                                {{ $sede->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Área

                                </div>
                                <div class="valor-edit">
                                    <select name="area_id" class="input-full">
                                        <option value="">Ninguno</option>
                                        @livewire('inventario.areas.foreach-select', ['areas' => $areas, 'area_id' => $elemento->area_id, 'disabled' => false, 'nivel' => ''])
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Notas
                                </div>
                                <div class="valor-edit">
                                    <textarea name="notas" class="input-full h-40" placeholder="Notas del elemento">{{ $elemento->notas }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="grid-datos-show">
                                @foreach ($elemento->categoria->caracteristicas as $caracteristica)
                                    <div class="atributo-edit">
                                        {{ $caracteristica->nombre }}
                                    </div>
                                    <div class="valor-edit">
                                        @if ($caracteristica->con_opciones)
                                            <select
                                                name="valor[{{ $elemento->findValor($caracteristica->id)->id ?? 'c' . $caracteristica->id }}]"
                                                class="input-full">
                                                @foreach ($caracteristica->opciones as $opcion)
                                                    <option value="{{ $opcion->nombre }}" @selected($elemento->findValor($caracteristica->id) ? $elemento->findValor($caracteristica->id)->valor == $opcion->nombre : false)>
                                                        {{ $opcion->nombre }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text"
                                                name="valor[{{ $elemento->findValor($caracteristica->id)->id ?? 'c' . $caracteristica->id }}]"
                                                value="{{ $elemento->findValor($caracteristica->id)->valor ?? '' }}"
                                                class="input-full" placeholder="">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
