

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('MesaDeEntradas/Entradas/Ver')
                    <x-nav-link href="{{ route('mesadeentradas.entradas.index') }}" :active="request()->routeIs('mesadeentradas.entradas.*')">
                        {{ __('Entradas') }}
                    </x-nav-link>
                    @endcan
                </div>

            