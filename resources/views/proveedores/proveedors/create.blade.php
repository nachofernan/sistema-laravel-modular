<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('proveedores.proveedors.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nuevo Proveedor
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('proveedores.proveedors.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="subtitulo-show">
                                Datos del Proveedor
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Razón Social *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="razonsocial" value="{{ old('razonsocial') }}" class="input-full" placeholder="Razón Social" required autocomplete="off">
                                    <div>@error('razonsocial') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    CUIT *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="cuit" value="{{ old('cuit') }}" class="input-full" placeholder="CUIT" required autocomplete="off">
                                    <div>@error('cuit') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Correo Institucional *
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="correo" value="{{ old('correo') }}" class="input-full" placeholder="Correo Institucional" required autocomplete="off">
                                    <div>@error('correo') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Nombre de Fantasía
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="fantasia" value="{{ old('fantasia') }}" class="input-full" placeholder="Nombre de Fantasía" autocomplete="off">
                                    <div>@error('fantasia') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Teléfono
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="telefono" value="{{ old('telefono') }}" class="input-full" placeholder="Teléfono" autocomplete="off">
                                    <div>@error('telefono') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Sitio Web
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="webpage" value="{{ old('webpage') }}" class="input-full" placeholder="Sitio Web" autocomplete="off">
                                    <div>@error('webpage') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Horario de atención
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="horario" value="{{ old('horario') }}" class="input-full" placeholder="Horario de atención" autocomplete="off">
                                    <div>@error('horario') {{ $message }} @enderror</div>
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