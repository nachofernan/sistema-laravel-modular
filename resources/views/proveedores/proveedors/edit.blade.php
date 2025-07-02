<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('proveedores.proveedors.update', $proveedor) }}" method="POST">
                @csrf
                @method('put')
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Editar Proveedor
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('proveedores.proveedors.show', $proveedor) }}">
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
                                    Razón Social
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="razonsocial" value="{{ $proveedor->razonsocial }}" class="input-full" placeholder="Razón Social" required autocomplete="off">
                                    <div>@error('razonsocial') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    CUIT
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="cuit" value="{{ $proveedor->cuit }}" class="input-full" placeholder="CUIT" required autocomplete="off">
                                    <div>@error('cuit') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Correo Institucional
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="correo" value="{{ $proveedor->correo }}" class="input-full" placeholder="Correo Institucional" required autocomplete="off">
                                    <div>@error('correo') {{ $message }} @enderror</div>
                                </div>
                                @can('Proveedores/Proveedores/Editar')
                                <div class="atributo-edit">
                                    Nombre de Fantasía
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="fantasia" value="{{ $proveedor->fantasia }}" class="input-full" placeholder="Nombre de Fantasía" autocomplete="off">
                                    <div>@error('fantasia') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Teléfono
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="telefono" value="{{ $proveedor->telefono }}" class="input-full" placeholder="Teléfono" autocomplete="off">
                                    <div>@error('telefono') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Sitio Web
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="webpage" value="{{ $proveedor->webpage }}" class="input-full" placeholder="Sitio Web" autocomplete="off">
                                    <div>@error('webpage') {{ $message }} @enderror</div>
                                </div>
                                <div class="atributo-edit">
                                    Horario de atención
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="horario" value="{{ $proveedor->horario }}" class="input-full" placeholder="Horario de atención" autocomplete="off">
                                    <div>@error('horario') {{ $message }} @enderror</div>
                                </div>
                                @endcan
                            </div>
                        </div>
                        <div class="col">
                            @can('Proveedores/Proveedores/EditarEstado')
                            <div class="subtitulo-show">
                                Actualizar Estado
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Nivel del Proveedor
                                </div>
                                <div class="valor-edit">
                                    <select name="estado_id" class="input-full">
                                        @foreach (\App\Models\Proveedores\Estado::all() as $estado)
                                            <option value="{{$estado->id}}" @selected($estado->id == $proveedor->estado->id)>{{$estado->estado}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="subtitulo-show mt-3">
                                En Litigio
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Proveedor en Litigio
                                </div>
                                <div class="valor-edit">
                                    <input type="checkbox" name="litigio" class="border-gray-400 rounded mt-2" @checked($proveedor->litigio)>
                                </div>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>