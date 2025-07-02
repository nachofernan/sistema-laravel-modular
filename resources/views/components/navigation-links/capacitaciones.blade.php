

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Capacitaciones/Capacitaciones/Ver')
                    <x-nav-link href="{{ route('capacitaciones.capacitacions.index') }}" :active="request()->routeIs('capacitaciones.capacitacions.*')">
                        Capacitaciones
                    </x-nav-link>
                    @endcan
                </div>

            