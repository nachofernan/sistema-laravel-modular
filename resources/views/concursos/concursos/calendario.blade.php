<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
            <div class="grid grid-cols-12 px-6 py-4 border-b">
                <div class="col-span-8 flex flex-col justify-center">
                    <h1 class="text-2xl font-semibold text-gray-800">Calendario de Cierres de Concursos</h1>
                </div>
                <div class="col-span-4 flex justify-end items-center">
                    <div class="flex space-x-4">
                        <!-- Selector de mes -->
                        <div class="relative">
                            <form action="{{ route('concursos.calendario') }}" method="GET" id="mesForm">
                                <input type="month" name="mes" id="mesSelector" 
                                       value="{{ $mes }}" 
                                       class="rounded-md border-gray-300 shadow-sm px-4 py-2"
                                       onchange="document.getElementById('mesForm').submit()">
                            </form>
                        </div>
                        
                        <!-- Botones de navegación -->
                        <div class="flex space-x-2 items-center">
                            <a href="{{ route('concursos.calendario', ['mes' => $mesPrevio]) }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                                <span class="sr-only">Mes anterior</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('concursos.calendario', ['mes' => $mesSiguiente]) }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md">
                                <span class="sr-only">Mes siguiente</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leyenda de estados -->
        <div class="flex flex-wrap gap-4 mb-4">
            <div class="flex items-center">
                <span class="h-4 w-4 rounded-full bg-orange-600 mr-2"></span>
                <span class="text-sm text-gray-700">En Precarga</span>
            </div>
            <div class="flex items-center">
                <span class="h-4 w-4 rounded-full bg-green-600 mr-2"></span>
                <span class="text-sm text-gray-700">Activos</span>
            </div>
            <div class="flex items-center">
                <span class="h-4 w-4 rounded-full bg-yellow-600 mr-2"></span>
                <span class="text-sm text-gray-700">Cerrados</span>
            </div>
            <div class="flex items-center">
                <span class="h-4 w-4 rounded-full bg-blue-600 mr-2"></span>
                <span class="text-sm text-gray-700">En Análisis</span>
            </div>
        </div>
        
        <!-- Calendario -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Encabezado del calendario -->
            <div class="grid grid-cols-7 bg-gray-100 text-gray-700 font-semibold border-b border-gray-200">
                <div class="py-2 px-3 text-center">Lun</div>
                <div class="py-2 px-3 text-center">Mar</div>
                <div class="py-2 px-3 text-center">Mié</div>
                <div class="py-2 px-3 text-center">Jue</div>
                <div class="py-2 px-3 text-center">Vie</div>
                <div class="py-2 px-3 text-center">Sáb</div>
                <div class="py-2 px-3 text-center">Dom</div>
            </div>
            
            <!-- Días del calendario -->
            <div class="grid grid-cols-7">
                @foreach ($diasCalendario as $dia)
                    <div class="border border-gray-200 min-h-[120px] p-2 {{ $dia['esHoy'] ? 'bg-blue-50' : '' }} {{ !$dia['perteneceMes'] ? 'bg-gray-50' : '' }}">
                        <div class="flex justify-between items-center mb-1">
                            <span class="{{ $dia['esHoy'] ? 'font-semibold text-blue-700' : ($dia['perteneceMes'] ? 'text-gray-700' : 'text-gray-400') }}">
                                {{ $dia['numero'] }}
                            </span>
                            
                            @if($dia['cierresConcursos'] > 0)
                                <span class="text-xs font-medium px-1.5 py-0.5 rounded-full bg-red-100 text-red-800">
                                    {{ $dia['cierresConcursos'] }}
                                </span>
                            @endif
                        </div>
                        
                        @if(count($dia['concursos']) > 0)
                            <div class="space-y-1 overflow-y-auto max-h-20">
                                @foreach($dia['concursos'] as $concurso)
                                    <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                                       class="block text-xs truncate hover:bg-gray-50 p-1 rounded"
                                       title="{{ $concurso->nombre }}">
                                        <span class="inline-block h-2 w-2 rounded-full mr-1
                                            {{ $concurso->estado_actual === 'precarga' ? 'bg-orange-600' : 
                                               ($concurso->estado_actual === 'activo' ? 'bg-green-600' : 
                                               ($concurso->estado_actual === 'cerrado' ? 'bg-yellow-600' : 'bg-blue-600')) }}">
                                        </span>
                                        <span class="truncate">{{ $concurso->nombre }}</span>
                                    </a>
                                @endforeach
                                
                                @if(count($dia['concursos']) > 3)
                                    <a href="{{ route('concursos.calendario.dia', ['fecha' => $dia['fecha']]) }}" 
                                       class="text-xs text-blue-600 hover:underline block text-center">
                                        Ver todos ({{ count($dia['concursos']) }})
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Listado de cierres próximos -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b border-gray-200 mb-4">
                Próximos cierres ({{ count($proximosCierres) }})
            </h2>
            
            <div class="space-y-4">
                @forelse($proximosCierres as $concurso)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $concurso->nombre }}</h3>
                                <span class="text-sm text-gray-500">#{{ $concurso->numero }}</span>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $concurso->estado_actual === 'precarga' ? 'bg-orange-100 text-orange-800' : 
                                       ($concurso->estado_actual === 'activo' ? 'bg-green-100 text-green-800' : 
                                       ($concurso->estado_actual === 'cerrado' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($concurso->estado_actual) }}
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mt-2">{{ $concurso->descripcion }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm mt-3">
                            <div>
                                <span class="text-gray-500 block">Inicio</span>
                                <span class="font-medium">{{ $concurso->fecha_inicio->format('d-m-Y H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Cierre</span>
                                <span class="font-medium">{{ $concurso->fecha_cierre->format('d-m-Y H:i') }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        No hay cierres programados para los próximos 30 días
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>