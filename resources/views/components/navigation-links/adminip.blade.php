

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('AdminIP/IPS/Ver')
                    <x-nav-link href="{{ route('adminip.ips.index') }}" :active="request()->routeIs('adminip.ips.*')">
                        IP's
                    </x-nav-link>
                    {{-- <x-nav-link href="{{ route('categorias.index') }}" :active="request()->routeIs('categorias.*') || request()->routeIs('categorias.*')">
                        Categorias
                    </x-nav-link> --}}
                    @endcan
                </div>

            