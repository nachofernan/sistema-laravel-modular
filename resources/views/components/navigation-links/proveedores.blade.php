

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Proveedores/Proveedores/Ver')
                    <x-nav-link href="{{ route('proveedores.proveedors.index') }}" :active="request()->routeIs('proveedores.proveedors.*') && !request()->routeIs('proveedores.proveedors.eliminados')">
                        Proveedores
                    </x-nav-link>
                    <x-nav-link href="{{ route('proveedores.proveedors.export') }}">
                        Exportar Excel
                    </x-nav-link>
                    @endcan
                    @can('Proveedores/Proveedores/EditarEstado')
                    <x-nav-link href="{{ route('proveedores.proveedors.eliminados') }}" :active="request()->routeIs('proveedores.proveedors.eliminados')">
                        Eliminados
                    </x-nav-link>
                    @endcan
                    @can('Proveedores/DocumentoTipos/Ver')
                    <x-nav-link href="{{ route('proveedores.documento_tipos.index') }}" :active="request()->routeIs('proveedores.documento_tipos.*')">
                        Tipos de Documentos
                    </x-nav-link>
                    @endcan
                    @can('Proveedores/Rubros/Ver')
                    <x-nav-link href="{{ route('proveedores.rubros.index') }}" :active="request()->routeIs('proveedores.rubros.*')">
                        Rubros y Subrubros
                    </x-nav-link>
                    @endcan
                    @can('Proveedores/Proveedores/EditarEstado')
                    <x-nav-link href="{{ route('proveedores.validacions.index') }}" :active="request()->routeIs('proveedores.validacions.*')">
                        Validaciones
                    </x-nav-link>
                    @endcan
                </div>

            