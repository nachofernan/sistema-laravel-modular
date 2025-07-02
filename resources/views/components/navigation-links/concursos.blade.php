

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @can('Concursos/Concursos/Ver')
                    <x-nav-link href="{{ route('concursos.concursos.index') }}"
                        :active="request()->routeIs('concursos.concursos.index') || 
                                (request()->routeIs('concursos.concursos.show') && 
                                request()->route('concurso')?->estado_id < 3) ||
                                request()->routeIs('concursos.concursos.create')">
                        Concursos Activos
                    </x-nav-link>
                    <x-nav-link href="{{ route('concursos.calendario') }}"
                        :active="request()->routeIs('concursos.calendario') || 
                                request()->routeIs('concursos.calendario.*')">
                        Calendario
                    </x-nav-link>
                    <x-nav-link href="{{ route('concursos.concursos.terminados') }}"
                        :active="request()->routeIs('concursos.concursos.terminados') ||
                                (request()->routeIs('concursos.concursos.show') && 
                                request()->route('concurso')?->estado_id >= 3)">
                        Concursos Terminados
                    </x-nav-link>
                    @endcan
                    @can('Concursos/DocumentoTipos/Ver')
                    <x-nav-link href="{{ route('concursos.documento_tipos.index') }}" :active="request()->routeIs('concursos.documento_tipos.*')">
                        Tipos de Documentos
                    </x-nav-link>
                    @endcan
                </div>

            