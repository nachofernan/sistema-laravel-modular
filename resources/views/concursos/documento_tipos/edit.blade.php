<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('concursos.documento_tipos.update', $documento_tipo) }}" method="POST">
                {{ csrf_field() }}
                @method('PUT')
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nuevo Tipo de Documento
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('concursos.documento_tipos.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Nombre *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="nombre" value="{{ $documento_tipo->nombre }}" class="input-full" placeholder="Nombre" required>
                                    <div>@error('nombre') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Descripción
                                </div>
                                <div class="valor-edit">
                                    <textarea type="text" name="descripcion" rows="4" class="input-full" placeholder="Descripción">{{ $documento_tipo->descripcion }}</textarea>
                                    <div>@error('descripcion') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    ¿Concurso u Oferta?
                                </div>
                                <div class="valor-edit">
                                    <select name="de_concurso" class="input-full">
                                        <option value="1" @selected($documento_tipo->de_concurso == 1)>Para Concursos</option>
                                        <option value="0" @selected($documento_tipo->de_concurso == 0)>Para Ofertas</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Campos solo para ofertas -->
                            <div id="campos-ofertas">
                                <div class="grid-datos-show mt-3 border-t-1 border-gray-200">
                                    <div class="atributo-edit">
                                    </div>
                                    <div class="valor-edit py-2 text-center">
                                        <small class="text-xs italic text-gray-600">Las siguientes opciones sólo se utilizan para los documentos de ofertas</small>
                                    </div>
                                    <div class="atributo-edit">
                                        Encriptado por defecto
                                    </div>
                                    <div class="valor-edit">
                                        <select name="encriptado" class="input-full">
                                            <option value="0" @selected($documento_tipo->encriptado == 0)>No</option>
                                            <option value="1" @selected($documento_tipo->encriptado == 1)>Si</option>
                                        </select>
                                    </div>
                                    <div class="atributo-edit">
                                        Obligatorio en Ofertas
                                    </div>
                                    <div class="valor-edit">
                                        <select name="obligatorio" class="input-full">
                                            <option value="0" @selected($documento_tipo->obligatorio == 0)>No</option>
                                            <option value="1" @selected($documento_tipo->obligatorio == 1)>Si</option>
                                        </select>
                                    </div>
                                    <div class="atributo-edit">
                                        Asociado a:
                                    </div>
                                    <div class="valor-edit">
                                        <select name="tipo_documento_proveedor_id" class="input-full">
                                            <option value="0">Sin Asociación</option>
                                            @foreach ($tipo_documento_proveedor as $tipo_documento)
                                            <option value="{{$tipo_documento->id}}" @selected($documento_tipo->tipo_documento_proveedor_id == $tipo_documento->id)>{{$tipo_documento->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deConcursoSelect = document.getElementById('de_concurso');
            const camposOfertas = document.getElementById('campos-ofertas');

            function toggleCamposOfertas() {
                if (deConcursoSelect.value === '1') {
                    // Para Concursos - ocultar campos
                    camposOfertas.style.display = 'none';
                } else {
                    // Para Ofertas - mostrar campos
                    camposOfertas.style.display = 'block';
                }
            }

            // Ejecutar al cargar la página
            toggleCamposOfertas();

            // Ejecutar cuando cambie el select
            deConcursoSelect.addEventListener('change', toggleCamposOfertas);
        });
    </script>
</x-app-layout>