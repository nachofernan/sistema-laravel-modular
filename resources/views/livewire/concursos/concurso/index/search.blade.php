<div class="mt-4">
    {{-- Buscadores y Filtros --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-8">
        <div class="space-y-4">
            {{-- Primera Fila: Búsqueda y Gestor --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="Buscar por número o nombre de concurso..."
                            class="block w-full pl-10 pr-3 py-2 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm">
                    </div>
                </div>

                <div class="md:col-span-4">
                    <select wire:model.live="gestor_id"
                        class="block w-full pl-3 pr-10 py-2 text-sm border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-lg transition duration-150 shadow-sm">
                        <option value="">Todos los gestores</option>
                        @foreach ($gestores as $gestor)
                            <option value="{{ $gestor->id }}">{{ $gestor->realname }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Segunda Fila: Selección de Estados --}}
            <div class="pt-2 border-t border-gray-50">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mr-2">Filtrar por:</span>
                    @foreach ($estados_disponibles as $estado)
                        <button wire:click="estado_update('{{ $estado['id'] }}')"
                            class="px-3 py-1.5 text-[11px] font-black uppercase rounded-full transition-all duration-200 {{ in_array($estado['id'], $estado_search) ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $estado['nombre'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Secciones por Estado --}}
    @php
        $seccionesConfig = [
            'precarga' => [
                'label' => 'En Precarga',
                'show_stats' => false,
                'dot' => 'bg-orange-600',
                'badge' => 'bg-orange-100 text-orange-800',
                'border' => 'hover:border-orange-200',
                'gradient' => 'from-orange-600 to-orange-700',
                'title' => 'group-hover:text-orange-700',
                'stats_bg' => 'bg-orange-50',
                'stats_border' => 'border-orange-100',
                'stats_text' => 'text-orange-700',
                'stats_label' => 'text-orange-600/70',
            ],
            'activo' => [
                'label' => 'Activos',
                'show_stats' => true,
                'dot' => 'bg-green-600',
                'badge' => 'bg-green-100 text-green-800',
                'border' => 'hover:border-green-200',
                'gradient' => 'from-green-600 to-green-700',
                'title' => 'group-hover:text-green-700',
                'stats_bg' => 'bg-green-50',
                'stats_border' => 'border-green-100',
                'stats_text' => 'text-green-700',
                'stats_label' => 'text-green-600/70',
            ],
            'cerrado' => [
                'label' => 'Cerrados',
                'show_stats' => true,
                'dot' => 'bg-yellow-600',
                'badge' => 'bg-yellow-100 text-yellow-800',
                'border' => 'hover:border-yellow-200',
                'gradient' => 'from-yellow-600 to-yellow-700',
                'title' => 'group-hover:text-yellow-700',
                'stats_bg' => 'bg-yellow-50',
                'stats_border' => 'border-yellow-100',
                'stats_text' => 'text-yellow-700',
                'stats_label' => 'text-yellow-600/70',
            ],
            'analisis' => [
                'label' => 'En Análisis',
                'show_stats' => true,
                'dot' => 'bg-blue-600',
                'badge' => 'bg-blue-100 text-blue-800',
                'border' => 'hover:border-blue-200',
                'gradient' => 'from-blue-600 to-blue-700',
                'title' => 'group-hover:text-blue-700',
                'stats_bg' => 'bg-blue-50',
                'stats_border' => 'border-blue-100',
                'stats_text' => 'text-blue-700',
                'stats_label' => 'text-blue-600/70',
            ],
            'vencido' => [
                'label' => 'Vencidos',
                'show_stats' => false,
                'dot' => 'bg-gray-600',
                'badge' => 'bg-gray-100 text-gray-800',
                'border' => 'hover:border-gray-200',
                'gradient' => 'from-gray-600 to-gray-700',
                'title' => 'group-hover:text-gray-700',
                'stats_bg' => 'bg-gray-50',
                'stats_border' => 'border-gray-100',
                'stats_text' => 'text-gray-700',
                'stats_label' => 'text-gray-600/70',
            ],
            'terminado' => [
                'label' => 'Terminados',
                'show_stats' => true,
                'dot' => 'bg-purple-600',
                'badge' => 'bg-purple-100 text-purple-800',
                'border' => 'hover:border-purple-200',
                'gradient' => 'from-purple-600 to-purple-700',
                'title' => 'group-hover:text-purple-700',
                'stats_bg' => 'bg-purple-50',
                'stats_border' => 'border-purple-100',
                'stats_text' => 'text-purple-700',
                'stats_label' => 'text-purple-600/70',
            ],
            'anulado' => [
                'label' => 'Anulados',
                'show_stats' => true,
                'dot' => 'bg-red-600',
                'badge' => 'bg-red-100 text-red-800',
                'border' => 'hover:border-red-200',
                'gradient' => 'from-red-600 to-red-700',
                'title' => 'group-hover:text-red-700',
                'stats_bg' => 'bg-red-50',
                'stats_border' => 'border-red-100',
                'stats_text' => 'text-red-700',
                'stats_label' => 'text-red-600/70',
            ],
        ];
    @endphp

    <div class="space-y-12 pb-10">
        @foreach ($ordenSecciones as $seccion)
            @if (isset($concursosAgrupados[$seccion]) && count($concursosAgrupados[$seccion]) > 0)
                @php
                    $config = $seccionesConfig[$seccion];
                @endphp
                <div class="section-group">
                    {{-- Header de Sección --}}
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-black text-gray-900 flex items-center">
                            <span class="h-4 w-4 rounded-full {{ $config['dot'] }} mr-3 shadow-md"></span>
                            {{ $config['label'] }}
                            <span class="ml-3 px-3 py-1 {{ $config['badge'] }} text-xs font-black rounded-full shadow-sm">
                                {{ count($concursosAgrupados[$seccion]) }}
                            </span>
                        </h2>
                    </div>

                    {{-- Grid de Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($concursosAgrupados[$seccion] as $concurso)
                            @php
                                $showStats = $config['show_stats'];
                            @endphp

                            <a href="{{ route('concursos.concursos.show', $concurso) }}"
                                class="group block h-full transition-all duration-300 hover:-translate-y-1">
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-2xl {{ $config['border'] }} transition-all overflow-hidden flex flex-col h-full">
                                    
                                    {{-- Cabecera Card --}}
                                    <div class="bg-gradient-to-br {{ $config['gradient'] }} px-5 py-3 flex justify-between items-center flex-shrink-0">
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-3xl font-black text-white leading-none opacity-80">#{{ $concurso->numero }}</span>
                                        </div>
                                        <div class="bg-white/10 p-1.5 rounded-lg group-hover:bg-white/20 transition-colors">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="p-5 flex-1 flex flex-col">
                                        <div class="mb-4">
                                            <h3 class="font-bold text-gray-800 text-lg leading-snug {{ $config['title'] }} transition-colors line-clamp-2">
                                                {{ $concurso->nombre }}
                                            </h3>
                                            <p class="mt-2 text-gray-500 text-sm line-clamp-2 leading-relaxed italic">
                                                {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                                            </p>
                                        </div>

                                        <div class="flex-1"></div>

                                        {{-- Gestor --}}
                                        <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-50 {{ $showStats ? 'mb-5' : '' }}">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0 border border-gray-100">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-[10px] uppercase tracking-tighter text-gray-400 font-bold leading-none">Gestor</span>
                                                    <span class="text-sm font-medium text-gray-700 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                                                </div>
                                            </div>

                                            <div class="w-20 {{ $config['stats_bg'] }} rounded-xl p-1.5 text-center border {{ $config['stats_border'] }} flex-shrink-0">
                                                <div class="text-lg font-black {{ $config['stats_text'] }} leading-none">
                                                    {{ count($concurso->invitaciones) }}
                                                </div>
                                                <div class="text-[8px] {{ $config['stats_label'] }} uppercase font-bold tracking-tight mt-0.5">
                                                    Invitados
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Estadísticas Completas --}}
                                        @if ($showStats)
                                            <div class="grid grid-cols-3 gap-2 mb-5">
                                                {{-- Rechazados --}}
                                                <div class="bg-red-50 rounded-xl p-2 text-center border border-red-100">
                                                    <div class="text-lg font-bold text-red-700 leading-none">{{ $concurso->invitaciones->where('intencion', 2)->count() }}</div>
                                                    <div class="text-[9px] text-red-600 uppercase font-bold tracking-tight mt-1">Rechazados</div>
                                                </div>
                                                {{-- Participarán --}}
                                                <div class="bg-green-50 rounded-xl p-2 text-center border border-green-100">
                                                    <div class="text-lg font-bold text-green-700 leading-none">{{ $concurso->invitaciones->where('intencion', 1)->count() }}</div>
                                                    <div class="text-[9px] text-green-600 uppercase font-bold tracking-tight mt-1">Intenciones</div>
                                                </div>
                                                {{-- Ofertas --}}
                                                <div class="bg-blue-50 rounded-xl p-2 text-center border border-blue-100">
                                                    <div class="text-lg font-bold text-blue-700 leading-none">{{ $concurso->invitaciones->where('intencion', 3)->count() }}</div>
                                                    <div class="text-[9px] text-blue-600 uppercase font-bold tracking-tight mt-1">Ofertas</div>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Footer con Fecha --}}
                                        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                            <div class="flex items-center text-gray-400">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-[10px] font-bold uppercase tracking-widest">Cierre</span>
                                            </div>
                                            <span class="text-xs font-bold text-gray-600">
                                                {{ $concurso->fecha_cierre->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if (count($concursosAgrupados) == 0)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-20 text-center">
                <svg class="w-20 h-20 text-gray-200 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No se encontraron resultados</h3>
                <p class="text-gray-500 text-lg">Prueba ajustando los criterios o habilitando más estados en los filtros.</p>
            </div>
        @endif
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</div>
