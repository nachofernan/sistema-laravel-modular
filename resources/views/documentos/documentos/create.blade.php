<x-app-layout>
    <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="block w-full overflow-x-auto py-1 px-5">
                <form action="{{ route('documentos.documentos.store') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                        <div class="col-span-8 pt-1">
                            <div class="titulo-show">
                                Crear Nuevo Documento
                            </div>
                        </div>
                        <div class="col-span-4 text-right text-sm">
                            <button type="submit" class="boton-celeste">Guardar</button>
                            <a href="{{ route('documentos.documentos.index') }}">
                                <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                            </a>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto pb-5 px-5">
                                <div class="grid-datos-show">
                                    <div class="atributo-edit">
                                        Nombre de Documento
                                    </div>
                                    <div class="valor-edit">
                                        <input type="text" name="nombre" class="input-full"
                                            placeholder="Nombre de Documento">
                                    </div>
                                    <div class="atributo-edit">
                                        Descripción
                                    </div>
                                    <div class="valor-edit">
                                        <input type="text" name="descripcion" class="input-full"
                                            placeholder="Descripción">
                                    </div>
                                    <div class="atributo-edit">
                                        Versión
                                    </div>
                                    <div class="valor-edit">
                                        <input type="text" name="version" class="input-full"
                                            placeholder="Versión">
                                    </div>
                                    <div class="atributo-edit">
                                        Archivo
                                    </div>
                                    <div class="valor-edit">
                                        <input type="file" name="archivo" class="input-full" placeholder="Archivo">
                                    </div>
                                    <div class="atributo-edit">
                                        Categoría
                                    </div>
                                    <div class="valor-edit">
                                        <select name="categoria_id" class="input-full">
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->id }}">
                                                    {{ $categoria->padre->nombre }}->{{ $categoria->nombre }}</option>
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
                                </div>

                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
