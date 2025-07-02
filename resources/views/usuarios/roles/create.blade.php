<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('usuarios.roles.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nuevo Rol
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('usuarios.roles.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="subtitulo-show">
                                Datos del Rol
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Nombre
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="name" class="input-full"
                                        placeholder="Nombre de Rol">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="subtitulo-show">
                                Permisos
                            </div>
                            <div class="text-sm">
                                @php
                                    $sistema = '';
                                @endphp
                                @foreach ($permissions as $permiso)
                                    @php
                                        $actual = explode('/', $permiso->name)[0];
                                    @endphp
                                    @if ($actual != $sistema)
                                        @php
                                            $sistema = $actual;
                                        @endphp
                                        <div class="mt-3 font-bold border-b">
                                            {{ $sistema }}
                                        </div>
                                    @endif
                                    <div class="hover:bg-gray-100 grid grid-cols-10 p-1">
                                        <div class="col-span-8 pl-3">
                                            {{ $permiso->name }}
                                        </div>
                                        <div class="col-span-2 text-center">
                                            <input type="checkbox" name="permissions[{{ $permiso->name }}]">
                                        </div>
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
