<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <!-- Header con estadísticas generales -->
        <div class="mb-8">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="grid grid-cols-12 px-6 py-4 border-b items-center">
                    <div class="col-span-8 flex flex-col justify-center">
                        <h1 class="text-2xl font-semibold text-gray-800">Gestión de Concursos</h1>
                        <p class="text-sm text-gray-600 mt-1">Administra y monitorea todos los procesos de licitación</p>
                    </div>
                    <div class="col-span-4 flex justify-end items-center">
                        @can('Concursos/Concursos/Crear')
                            <a href="{{ route('concursos.concursos.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Crear Nuevo Concurso
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

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
                   class="group block transition-all duration-200 ease-in-out hover:scale-[1.02]">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-orange-300 transition-all duration-200 overflow-hidden">
                        <!-- Header con gradiente -->
                        <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-lg leading-tight truncate">{{ $concurso->nombre }}</h3>
                                    <p class="text-orange-100 text-sm">#{{ $concurso->numero }}</p>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    <svg class="w-5 h-5 text-orange-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6 space-y-4">
                            <!-- Descripción -->
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 min-h-[2.5rem]">
                                {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                            </p>
                            
                            <!-- Gestor -->
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-500 mr-2">Gestor:</span>
                                <span class="font-medium text-gray-900 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                            </div>

                            <!-- Métricas -->
                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-orange-600">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Invitados</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-700">{{ $concurso->invitaciones->filter(function ($invitacion) {return $invitacion->intencion == 3;})->count() }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Ofertas</div>
                                </div>
                            </div>

                            <!-- Fecha de cierre -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Cierre</span>
                                <span class="font-medium text-gray-900">{{ $concurso->fecha_cierre->format('d/m/Y H:i') }}</span>
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
                   class="group block transition-all duration-200 ease-in-out hover:scale-[1.02]">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-green-300 transition-all duration-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-lg leading-tight truncate">{{ $concurso->nombre }}</h3>
                                    <p class="text-green-100 text-sm">#{{ $concurso->numero }}</p>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 min-h-[2.5rem]">
                                {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                            </p>
                            
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-500 mr-2">Gestor:</span>
                                <span class="font-medium text-gray-900 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Invitados</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-700">{{ $concurso->invitaciones->filter(function ($invitacion) {return $invitacion->intencion == 3;})->count() }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Ofertas</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Cierre</span>
                                <span class="font-medium text-gray-900">{{ $concurso->fecha_cierre->format('d/m/Y H:i') }}</span>
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
                   class="group block transition-all duration-200 ease-in-out hover:scale-[1.02]">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-yellow-300 transition-all duration-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 text-white px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-lg leading-tight truncate">{{ $concurso->nombre }}</h3>
                                    <p class="text-yellow-100 text-sm">#{{ $concurso->numero }}</p>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 min-h-[2.5rem]">
                                {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                            </p>
                            
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-500 mr-2">Gestor:</span>
                                <span class="font-medium text-gray-900 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Invitados</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-700">{{ $concurso->invitaciones->filter(function ($invitacion) {return $invitacion->intencion == 3;})->count() }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Ofertas</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Cierre</span>
                                <span class="font-medium text-gray-900">{{ $concurso->fecha_cierre->format('d/m/Y H:i') }}</span>
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
                   class="group block transition-all duration-200 ease-in-out hover:scale-[1.02]">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-blue-300 transition-all duration-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-lg leading-tight truncate">{{ $concurso->nombre }}</h3>
                                    <p class="text-blue-100 text-sm">#{{ $concurso->numero }}</p>
                                </div>
                                <div class="ml-3 flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-200 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <p class="text-gray-600 text-sm leading-relaxed line-clamp-2 min-h-[2.5rem]">
                                {{ $concurso->descripcion ?? 'Sin descripción disponible' }}
                            </p>
                            
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-500 mr-2">Gestor:</span>
                                <span class="font-medium text-gray-900 truncate">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ count($concurso->invitaciones) }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Invitados</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-gray-700">{{ $concurso->invitaciones->filter(function ($invitacion) {return $invitacion->intencion == 3;})->count() }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Ofertas</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500 uppercase tracking-wide">Cierre</span>
                                <span class="font-medium text-gray-900">{{ $concurso->fecha_cierre->format('d/m/Y H:i') }}</span>
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