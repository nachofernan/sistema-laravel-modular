

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Fichadas/Fichadas/Ver')
                    <x-nav-link href="{{ route('fichadas.fichadas.index') }}" :active="request()->routeIs('fichadas.fichadas.*')">
                        {{ __('Fichadas') }}
                    </x-nav-link>
                    @endcan
                </div>

            