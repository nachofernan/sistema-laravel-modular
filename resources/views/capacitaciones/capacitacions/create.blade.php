<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('capacitaciones.capacitacions.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nueva Capacitación
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('capacitaciones.capacitacions.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="subtitulo-show">
                                Datos de la Capacitación
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Nombre *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="input-full" placeholder="Nombre" required>
                                </div>
                                <div class="atributo-edit">
                                    Fecha *
                                </div>
                                <div class="valor-edit">
                                    <input type="date" name="fecha" value="{{ old('fecha') }}" class="input-full" required>
                                </div>
                                <div class="atributo-edit">
                                    Descripción
                                </div>
                                <div class="valor-edit">
                                    <textarea name="descripcion" rows="10" class="input-full">{{ old('descripcion') }}</textarea>
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