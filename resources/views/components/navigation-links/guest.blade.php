

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Buscador') }}
                    </x-nav-link>
                    @foreach (App\Models\Documentos\Categoria::whereNull('categoria_padre_id')->get() as $categoria)

                    <x-nav-link href="{{ route('home.documentos.categoria', $categoria) }}" :active="url()->current() == route('home.documentos.categoria', ['categoria' => $categoria])">
                        {{ $categoria->nombre }}
                    </x-nav-link>

                    @endforeach
                    
                    <x-nav-link href="{{ route('home.dashboard') }}" :active="
                                                                request()->routeIs('home.dashboard') || 
                                                                request()->routeIs('home.capacitacions.*') || 
                                                                request()->routeIs('home.tickets.*') || 
                                                                request()->routeIs('home.encuestas.*')">
                        {{ __('Mi Portal') }}
                        @if (!Auth::user())
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="15" height="15" viewBox="0 0 24 24" style="margin-top: -3px; margin-left: 3px;">
                            <path fill="#888888" d="M 12 1 C 8.6761905 1 6 3.6761905 6 7 L 6 8 C 4.9069372 8 4 8.9069372 4 10 L 4 20 C 4 21.093063 4.9069372 22 6 22 L 18 22 C 19.093063 22 20 21.093063 20 20 L 20 10 C 20 8.9069372 19.093063 8 18 8 L 18 7 C 18 3.6761905 15.32381 1 12 1 z M 12 3 C 14.27619 3 16 4.7238095 16 7 L 16 8 L 8 8 L 8 7 C 8 4.7238095 9.7238095 3 12 3 z M 6 10 L 18 10 L 18 20 L 6 20 L 6 10 z M 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 C 13.1 17 14 16.1 14 15 C 14 13.9 13.1 13 12 13 z"></path>
                        </svg>
                        @endif
                    </x-nav-link>
                </div>

            