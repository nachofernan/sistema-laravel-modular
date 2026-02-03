<x-app-layout>
    <x-page-header title="Gestión de Concursos">
        <x-slot:actions>
            @can('Concursos/Concursos/Crear')
                <a href="{{ route('concursos.concursos.create') }}"
                    class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    + Nuevo Concurso
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>
    <div class="max-w-7xl mx-auto">
        <!-- Header con estadísticas generales -->
        <div class="mb-8">

            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="h-3 w-3 rounded-full bg-orange-600 mr-2"></div>
                        <span class="text-sm font-medium text-orange-700">En Precarga</span>
                    </div>
                    <p class="text-2xl font-bold text-orange-900 mt-1">{{ $precargas->count() }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="h-3 w-3 rounded-full bg-green-600 mr-2"></div>
                        <span class="text-sm font-medium text-green-700">Activos</span>
                    </div>
                    <p class="text-2xl font-bold text-green-900 mt-1">{{ $activos->count() }}</p>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="h-3 w-3 rounded-full bg-yellow-600 mr-2"></div>
                        <span class="text-sm font-medium text-yellow-700">Cerrados</span>
                    </div>
                    <p class="text-2xl font-bold text-yellow-900 mt-1">{{ $cerrados->count() }}</p>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="h-3 w-3 rounded-full bg-blue-600 mr-2"></div>
                        <span class="text-sm font-medium text-blue-700">En Análisis</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 mt-1">{{ $analisis->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Concursos en Precarga -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="h-4 w-4 rounded-full bg-orange-600 mr-3"></span>
                    En Precarga
                    <span class="ml-2 px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">{{ $precargas->count() }}</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($precargas as $concurso)
                <a href="{{ route('concursos.concursos.show', $concurso) }}" 
   class="group block h-full transition-all duration-300 hover:-translate-y-1">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-orange-200 transition-all overflow-hidden flex flex-col h-full">
        
        <div class="bg-gradient-to-br from-orange-600 to-orange-700 px-5 py-3 flex justify-between items-center flex-shrink-0">
            <div class="flex items-baseline gap-1">
                <span class="text-3xl font-black text-white leading-none opacity-80">#{{ $concurso->numero }}</span>
            </div>
            <div class="bg-white/10 p-1.5 rounded-lg group-hover:bg-white/20 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </div>
        
        <div class="p-5 flex-1 flex flex-col">
            <div class="mb-4">
                <h3 class="font-bold text-gray-800 text-lg leading-snug group-hover:text-orange-700 transition-colors line-clamp-2">
                    {{ $concurso->nombre }}
                </h3>
                <p class="mt-2 text-gray-500 text-sm line-clamp-2 leading-relaxed italic">
                    {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                </p>
            </div>

            <div class="flex-1"></div>

            <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-50 mb-5">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0 border border-gray-100">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[10px] uppercase tracking-tighter text-gray-400 font-bold leading-none">Gestor</span>
                        <span class="text-sm font-medium text-gray-700 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                    </div>
                </div>

                <div class="w-24 bg-orange-50 rounded-xl p-2 text-center border border-orange-100 flex-shrink-0">
                    <div class="text-xl font-black text-orange-700 leading-none">
                        {{ count($concurso->invitaciones) }}
                    </div>
                    <div class="text-[9px] text-orange-600/70 uppercase font-bold tracking-tight mt-1">
                        Invitados
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                <div class="flex items-center text-gray-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Cierre</span>
                </div>
                <span class="text-sm font-bold text-gray-800">
                    {{ $concurso->fecha_cierre->format('d/m/Y H:i') }}
                </span>
            </div>
        </div>
    </div>
</a>
                @endforeach
            </div>
        </div>

        <!-- Concursos Activos -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="h-4 w-4 rounded-full bg-green-600 mr-3"></span>
                    Activos
                    <span class="ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">{{ $activos->count() }}</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($activos as $concurso)
                <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                class="group block h-full transition-all duration-300 hover:-translate-y-1">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-green-200 transition-all overflow-hidden flex flex-col h-full">
                        
                        <div class="bg-gradient-to-br from-green-600 to-green-700 px-5 py-3 flex justify-between items-center flex-shrink-0">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-white leading-none opacity-70">#{{ $concurso->numero }}</span>
                            </div>
                            <div class="bg-white/10 p-1.5 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            
                            <div class="mb-4">
                                <h3 class="font-bold text-gray-800 text-lg leading-snug group-hover:text-green-700 transition-colors line-clamp-2">
                                    {{ $concurso->nombre }}
                                </h3>
                                <p class="mt-2 text-gray-500 text-sm line-clamp-2 leading-relaxed italic">
                                    {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                                </p>
                            </div>

                            <div class="flex-1"></div>

                            <div class="flex items-center gap-2 mb-5 pt-4 border-t border-gray-50">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[10px] uppercase tracking-tighter text-gray-400 font-bold leading-none">Gestor</span>
                                    <span class="text-sm font-medium text-gray-700 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-5">
                                <div class="flex-1 bg-green-50 rounded-xl p-2 text-center border border-green-100/50">
                                    <div class="text-lg font-bold text-green-700">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-[10px] text-green-600/70 uppercase font-bold tracking-tight">Invitados</div>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                    <div class="text-lg font-bold text-gray-700">{{ $concurso->invitaciones->where('intencion', 1)->count() }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Intenciones</div>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                    <div class="text-lg font-bold text-gray-700">{{ $concurso->invitaciones->where('intencion', 3)->count() }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Ofertas</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <div class="flex items-center text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Cierre</span>
                                </div>
                                <span class="text-lg font-bold text-gray-500">
                                    {{ $concurso->fecha_cierre->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Concursos Cerrados -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="h-4 w-4 rounded-full bg-yellow-600 mr-3"></span>
                    Cerrados
                    <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">{{ $cerrados->count() }}</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($cerrados as $concurso)
                <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                class="group block h-full transition-all duration-300 hover:-translate-y-1">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-yellow-200 transition-all overflow-hidden flex flex-col h-full">
                        
                        <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 px-5 py-3 flex justify-between items-center flex-shrink-0">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-white leading-none opacity-70">#{{ $concurso->numero }}</span>
                            </div>
                            <div class="bg-white/10 p-1.5 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            
                            <div class="mb-4">
                                <h3 class="font-bold text-gray-800 text-lg leading-snug group-hover:text-yellow-700 transition-colors line-clamp-2">
                                    {{ $concurso->nombre }}
                                </h3>
                                <p class="mt-2 text-gray-500 text-sm line-clamp-2 leading-relaxed italic">
                                    {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                                </p>
                            </div>

                            <div class="flex-1"></div>

                            <div class="flex items-center gap-2 mb-5 pt-4 border-t border-gray-50">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[10px] uppercase tracking-tighter text-gray-400 font-bold leading-none">Gestor</span>
                                    <span class="text-sm font-medium text-gray-700 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-5">
                                <div class="flex-1 bg-green-50 rounded-xl p-2 text-center border border-green-100/50">
                                    <div class="text-lg font-bold text-green-700">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-[10px] text-green-600/70 uppercase font-bold tracking-tight">Invitados</div>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                    <div class="text-lg font-bold text-gray-700">{{ $concurso->invitaciones->where('intencion', 1)->count() }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Intenciones</div>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                    <div class="text-lg font-bold text-gray-700">{{ $concurso->invitaciones->where('intencion', 3)->count() }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Ofertas</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <div class="flex items-center text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Cierre</span>
                                </div>
                                <span class="text-lg font-bold text-gray-500">
                                    {{ $concurso->fecha_cierre->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Concursos en Análisis -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="h-4 w-4 rounded-full bg-blue-600 mr-3"></span>
                    En Análisis
                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $analisis->count() }}</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($analisis as $concurso)
                <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                class="group block h-full transition-all duration-300 hover:-translate-y-1">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-200 transition-all overflow-hidden flex flex-col h-full">
                        
                        <div class="bg-gradient-to-br from-blue-600 to-blue-700 px-5 py-3 flex justify-between items-center flex-shrink-0">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-white leading-none opacity-70">#{{ $concurso->numero }}</span>
                            </div>
                            <div class="bg-white/10 p-1.5 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            
                            <div class="mb-4">
                                <h3 class="font-bold text-gray-800 text-lg leading-snug group-hover:text-blue-700 transition-colors line-clamp-2">
                                    {{ $concurso->nombre }}
                                </h3>
                                <p class="mt-2 text-gray-500 text-sm line-clamp-2 leading-relaxed italic">
                                    {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                                </p>
                            </div>

                            <div class="flex-1"></div>

                            <div class="flex items-center gap-2 mb-5 pt-4 border-t border-gray-50">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[10px] uppercase tracking-tighter text-gray-400 font-bold leading-none">Gestor</span>
                                    <span class="text-sm font-medium text-gray-700 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-5">
                                <div class="flex-1 bg-green-50 rounded-xl p-2 text-center border border-green-100/50">
                                    <div class="text-lg font-bold text-green-700">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-[10px] text-green-600/70 uppercase font-bold tracking-tight">Invitados</div>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                    <div class="text-lg font-bold text-gray-700">{{ $concurso->invitaciones->where('intencion', 1)->count() }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Intenciones</div>
                                </div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-2 text-center border border-gray-100">
                                    <div class="text-lg font-bold text-gray-700">{{ $concurso->invitaciones->where('intencion', 3)->count() }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Ofertas</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <div class="flex items-center text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Cierre</span>
                                </div>
                                <span class="text-lg font-bold text-gray-500">
                                    {{ $concurso->fecha_cierre->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Concursos Vencidos -->
        <div class="mb-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="h-4 w-4 rounded-full bg-gray-600 mr-3"></span>
                    Vencidos
                    <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">{{ $vencidos->count() }}</span>
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($vencidos as $concurso)
                <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                class="group block h-full transition-all duration-300 hover:-translate-y-1">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:border-gray-200 transition-all overflow-hidden flex flex-col h-full">
                        
                        <div class="bg-gradient-to-br from-gray-600 to-gray-700 px-5 py-3 flex justify-between items-center flex-shrink-0">
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-white leading-none opacity-70">#{{ $concurso->numero }}</span>
                            </div>
                            <div class="bg-white/10 p-1.5 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            
                            <div class="mb-4">
                                <h3 class="font-bold text-gray-800 text-lg leading-snug group-hover:text-gray-700 transition-colors line-clamp-2">
                                    {{ $concurso->nombre }}
                                </h3>
                                <p class="mt-2 text-gray-500 text-sm line-clamp-2 leading-relaxed italic">
                                    {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                                </p>
                            </div>

                            <div class="flex-1"></div>

                            <div class="flex items-center gap-2 mb-5 pt-4 border-t border-gray-50">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 flex-shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[10px] uppercase tracking-tighter text-gray-400 font-bold leading-none">Gestor</span>
                                    <span class="text-sm font-medium text-gray-700 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <div class="flex items-center text-gray-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Cierre</span>
                                </div>
                                <span class="text-lg font-bold text-gray-500">
                                    {{ $concurso->fecha_cierre->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>