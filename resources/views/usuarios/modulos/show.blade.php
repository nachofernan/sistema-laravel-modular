<x-app-layout>
    <div class="w-full xl:w-10/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded ">
            <div class="grid grid-cols-12 px-5 py-3 border-b pb-3">
                <div class="col-span-8">
                    <div class="titulo-show">
                        {{ $modulo->nombre }}
                    </div>
                    <div class="text-xs">
                        {{ $modulo->descripcion }}
                    </div>
                </div>
                <div class="col-span-4 text-right pt-3 text-sm">
                    @can('Usuarios/Modulos/Editar')
                        <a href="{{ route('usuarios.modulos.edit', $modulo) }}" class="boton-celeste">Editar</a>
                    @endcan
                </div>
            </div>
            <div class="w-full overflow-x-auto py-5 px-5 grid grid-cols-2 gap-6">
                <div class="col">
                    <div class="subtitulo-show grid grid-cols-2">
                        <div class="col mt-1">
                            Roles y sus permisos
                        </div>
                        <div class="col text-right">
                            @livewire('usuarios.modulos.show.create-role', ['modulo' => $modulo], key($modulo->id.microtime(true)))
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-x-6 text-sm">
                        @foreach ($modulo->roles() as $role)
                            <div class="col-span-6 font-bold pt-2 mt-2 border-b">
                                <div class="grid grid-cols-6 gap-x-6 hover:bg-gray-100 px-3">
                                    <div class="col-span-4 my-1">
                                        {{ $role->name }}
                                    </div>
                                    <div class="col-span-2 my-1">
                                        @livewire('usuarios.modulos.show.edit-role', ['modulo' => $modulo, 'role' => $role], key($role->id.microtime(true)))
                                    </div>
                                </div>
                            </div>
                            <div class="col-start-2 col-span-4">
                                @foreach ($role->permissions as $permission)
                                    {{ $permission->name }}<br>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col">
                    <div class="subtitulo-show grid grid-cols-2">
                        <div class="col mt-1">
                            Permisos
                        </div>
                        <div class="col text-right">
                            @livewire('usuarios.modulos.show.create-permission', ['modulo' => $modulo], key($modulo->id.microtime(true)))
                        </div>
                    </div>
                    <div class="text-sm">
                        @foreach ($modulo->permisos() as $permiso)
                        <div class="grid grid-cols-6 gap-x-6 hover:bg-gray-100 px-3">
                            <div class="col-span-4 my-1">
                                {{ $permiso->name }}
                            </div>
                            <div class="col-span-2 my-1">
                                @livewire('usuarios.modulos.show.edit-permission', ['modulo' => $modulo, 'permiso' => $permiso], key($permiso->id.microtime(true)))
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
