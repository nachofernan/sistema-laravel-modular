<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <form action="{{ route('usuarios.users.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="grid grid-cols-12 px-5 py-3 border-b pb-3 mb-2">
                    <div class="col-span-8 pt-1">
                        <div class="titulo-show">
                            Crear Nuevo Usuario
                        </div>
                    </div>
                    <div class="col-span-4 text-right text-sm">
                        <button type="submit" class="boton-celeste">Guardar</button>
                        <a href="{{ route('usuarios.users.index') }}">
                            <button type="button" class="bg-gray-200 boton-celeste">Volver</button>
                        </a>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto pb-5 px-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col">
                            <div class="subtitulo-show">
                                Datos de usuario y empresariales
                            </div>
                            <div class="grid-datos-show">
                                <div class="atributo-edit">
                                    Visibilidad en buscador
                                </div>
                                <div class="valor-edit">
                                    <input type="checkbox" name="visible" class="mt-2 rounded" checked>
                                </div>
                                <div class="atributo-edit">
                                    Nombre de Usuario
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="name" class="input-full" placeholder="Nombre de Usuario">
                                </div>
                                <div class="atributo-edit">
                                    Nombre Real
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="realname" class="input-full" placeholder="Nombre Real">
                                </div>
                                <div class="atributo-edit">
                                    Correo Electrónico
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="email" class="input-full" placeholder="Correo Electrónico">
                                </div>
                                <div class="atributo-edit">
                                    Legajo
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="legajo" class="input-full" placeholder="Legajo">
                                </div>
                                <div class="atributo-edit">
                                    Interno
                                </div>
                                <div class="valor-edit">
                                    <input type="text" name="interno" class="input-full" placeholder="Interno">
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
                                        @livewire('usuarios.areas.foreach-select', ['areas' => $areas, 'area_id' => 0, 'disabled' => false, 'nivel' => ''])
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            @can('Usuarios/Usuarios/Roles')
                            <div class="subtitulo-show">
                                Accesos y Roles
                            </div>
                            <div class="text-sm">
                                @foreach ($modulos as $modulo)
                                    <div class="mt-3 font-bold border-b">
                                        {{ $modulo->nombre }}
                                        @if ($modulo->estado == 'mantenimiento')
                                        <span class="ml-4 font-bold bg-red-800 text-white text-xs rounded px-2 py-0">Mantenimiento</span>
                                        @endif
                                    </div>
                                    @foreach ($modulo->roles() as $role)
                                    <div class="hover:bg-gray-100 grid grid-cols-10 p-1">
                                        <div class="col-span-8 pl-3">
                                            {{ $role->name }}
                                        </div>
                                        <div class="col-span-2 text-center">
                                            <input type="checkbox" name="roles[{{ $role->name }}]">
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                            @endcan
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>