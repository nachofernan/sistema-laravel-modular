

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Usuarios/Usuarios/Ver')
                    <x-nav-link href="{{ route('usuarios.users.index') }}" :active="request()->routeIs('usuarios.users.*') && !request()->routeIs('usuarios.users.trashed')">
                        Usuarios
                    </x-nav-link>
                    @endcan
                    @can('Usuarios/Usuarios/Eliminar')
                    <x-nav-link href="{{ route('usuarios.users.trashed') }}" :active="request()->routeIs('usuarios.users.trashed')">
                        Borrados
                    </x-nav-link>
                    @endcan
                    @can('Usuarios/Areas/Ver')
                    <x-nav-link href="{{ route('usuarios.areas.index') }}" :active="request()->routeIs('usuarios.areas.*')">
                        Areas
                    </x-nav-link>
                    @endcan
                    @can('Usuarios/Sedes/Ver')
                    <x-nav-link href="{{ route('usuarios.sedes.index') }}" :active="request()->routeIs('usuarios.sedes.*')">
                        Sedes
                    </x-nav-link>
                    @endcan
                    @can('Usuarios/Modulos/Ver')
                    <x-nav-link href="{{ route('usuarios.modulos.index') }}" :active="request()->routeIs('usuarios.modulos.*')">
                        Módulos
                    </x-nav-link>
                    <x-nav-link href="{{ route('usuarios.email-queue.index') }}" :active="request()->routeIs('usuarios.email-queue.*')">
                        Cola de Correos
                    </x-nav-link>
                    @endcan
                </div>

            