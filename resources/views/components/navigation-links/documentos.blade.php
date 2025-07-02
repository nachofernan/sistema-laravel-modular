

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Documentos/Documentos/Ver')
                    <x-nav-link href="{{ route('documentos.documentos.index') }}" :active="request()->routeIs('documentos.documentos.*')">
                        {{ __('Documentos') }}
                    </x-nav-link>
                    @endcan
                    @can('Documentos/Categorias/Ver')
                    <x-nav-link href="{{ route('documentos.categorias.index') }}" :active="request()->routeIs('documentos.categorias.*')">
                        {{ __('Categorias') }}
                    </x-nav-link>
                    @endcan
                </div>

            