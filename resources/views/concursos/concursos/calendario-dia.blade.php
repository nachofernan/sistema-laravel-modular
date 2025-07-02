<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Concursos con cierre: {{ $fechaCarbon->format('d/m/Y') }}
            </h1>
            
            <div class="flex space-x-2">
                <a href="{{ route('concursos.calendario') }}" 
                   class="flex items-center text-blue-600 hover:text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Volver al calendario
                </a>
            </div>
        </div>
        
        <div class="bg-white shadow rounded-lg p-6">
            @if($concursos->count() > 0)
                <div class="space-y-6">
                    @foreach($concursos as $concurso)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $concurso->nombre }}</h2>
                                    <p class="text-sm text-gray-500">ID: #{{ $concurso->numero }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $concurso->estado_actual === 'precarga' ? 'bg-orange-100 text-orange-800' : 
                                           ($concurso->estado_actual === 'activo' ? 'bg-green-100 text-green-800' : 
                                           ($concurso->estado_actual === 'cerrado' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($concurso->estado_actual) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-gray-700">{{ $concurso->descripcion }}</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Fecha de Inicio</h3>
                                    <p class="text-gray-900">{{ $concurso->fecha_inicio->format('d-m-Y H:i') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Fecha de Cierre</h3>
                                    <p class="text-gray-900">{{ $concurso->fecha_cierre->format('d-m-Y H:i') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Tiempo Restante</h3>
                                    <p class="text-gray-900">
                                        @if($concurso->fecha_cierre->isPast())
                                            Cerrado
                                        @else
                                            {{ $concurso->fecha_cierre->diffForHumans(['parts' => 2]) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('concursos.concursos.show', $concurso) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay concursos</h3>
                    <p class="mt-1 text-sm text-gray-500">No hay concursos programados para cerrar en esta fecha.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>