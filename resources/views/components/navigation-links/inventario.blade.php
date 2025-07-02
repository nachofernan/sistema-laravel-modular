

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Inventario/Elementos/Ver')
                    <x-nav-link href="{{ route('inventario.elementos.index') }}" :active="request()->routeIs('inventario.elementos.*')">
                        Inventario
                    </x-nav-link>
                    @endcan
                    @can('Inventario/Categorias/Ver')
                    <x-nav-link href="{{ route('inventario.categorias.index') }}" :active="request()->routeIs('inventario.categorias.*')">
                        Categorias
                    </x-nav-link>
                    @endcan
                    @can('Inventario/Usuarios/Ver')
                    <x-nav-link href="{{ route('inventario.users.index') }}" :active="request()->routeIs('inventario.users.*')">
                        Usuarios
                    </x-nav-link>
                    @endcan
                    @can('Inventario/Perifericos/Ver')
                    <x-nav-link href="{{ route('inventario.perifericos.index') }}" :active="request()->routeIs('inventario.perifericos.*')">
                        Periféricos
                    </x-nav-link>
                    @endcan
                </div>

            