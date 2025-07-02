<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('proveedores.documento_tipos.update', $documentoTipo) }}" method="POST">
                {{ csrf_field() }}
                @method('put')
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Editar Datos del Tipo de Documento
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('proveedores.documento_tipos.show', $documentoTipo) }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="subtitulo-show">
                                Datos del Tipo de Documento
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Código *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="codigo" value="{{ $documentoTipo->codigo }}" class="input-full" placeholder="Código" required>
                                    <div>@error('codigo') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Nombre *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="nombre" value="{{ $documentoTipo->nombre }}" class="input-full" placeholder="Nombre" required>
                                    <div>@error('nombre') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    ¿Tiene vencimiento?
                                </div>
                                <div class="valor-edit">
                                    <select name="vencimiento" class="input-full">
                                        <option value="0">No</option>
                                        <option value="1" @selected($documentoTipo->vencimiento)>Si</option>
                                    </select>
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