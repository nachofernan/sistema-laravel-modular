<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('inventario.elementos.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nuevo Elemento
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('inventario.elementos.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Categoría
                                </div>
                                <div class="valor-edit">
                                    <select name="categoria_id" class="input-full">
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}
                                                ({{ $categoria->prefijo }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Estado
                                </div>
                                <div class="valor-edit">
                                    <select name="estado_id" class="input-full">
                                        @foreach ($estados as $estado)
                                            <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Proveedor
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="proveedor" class="input-full" placeholder="Proveedor">
                                </div>
                                <div class="atributo-edit">
                                    Soporte
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="soporte" class="input-full" placeholder="Soporte">
                                </div>
                                <div class="atributo-edit">
                                    Vencimiento de Garantía
                                </div>
                                <div class="valor-edit">
                                    <input type="date" name="vencimiento_garantia" class="input-full"
                                        placeholder="Vencimiento de Garantía">
                                </div>
                                <div class="atributo-edit">
                                    Usuario
                                </div>
                                <div class="valor-edit">
                                    <select name="user_id" class="input-full">
                                        <option value="">Ninguno</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->realname }}</option>
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
                                            <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Área

                                </div>
                                <div class="valor-edit">
                                    <select name="area_id" class="input-full">
                                        <option value="">Ninguno</option>
                                        @livewire('inventario.areas.foreach-select', ['areas' => $areas, 'area_id' => 0, 'disabled' => false, 'nivel' => ''])
                                    </select>
                                </div>
                                <div class="atributo-edit">
                                    Notas
                                </div>
                                <div class="valor-edit">
                                    <textarea name="notas" class="input-full h-40" placeholder="Notas del elemento"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col">

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>
