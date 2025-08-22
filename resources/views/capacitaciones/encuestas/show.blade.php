<x-app-layout>
    <x-page-header title="{{ $encuesta->nombre }}">
        <x-slot:subtitle>
            <a href="{{ route('capacitaciones.capacitacions.show', $encuesta->capacitacion) }}" 
               class="text-blue-600 hover:text-blue-800 text-sm">
                ← Volver a {{ $encuesta->capacitacion->nombre }}
            </a>
        </x-slot:subtitle>
        <x-slot:actions>
            @can('Capacitaciones/Encuestas/Editar')
                @livewire('capacitaciones.encuesta.show.editar-encuesta', ['encuesta' => $encuesta], key($encuesta->id.microtime(true)))
            @endcan
        </x-slot:actions>
    </x-page-header>

    <div class="w-full max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información de la Encuesta -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Datos Generales -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Nombre:</div>
                            <div class="col-span-2 text-sm text-gray-900 font-medium">{{ $encuesta->nombre }}</div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Capacitación:</div>
                            <div class="col-span-2 text-sm">
                                <a href="{{ route('capacitaciones.capacitacions.show', $encuesta->capacitacion) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $encuesta->capacitacion->nombre }}
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Estado:</div>
                            <div class="col-span-2 text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                             {{ $encuesta->estado == 0 ? 'bg-gray-100 text-gray-800' : 
                                                ($encuesta->estado == 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($encuesta->estado()['nombre']) }}
                                </span>
                            </div>
                        </div>

                        @if($encuesta->descripcion)
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-sm font-medium text-gray-500">Descripción:</div>
                            <div class="col-span-2 text-sm text-gray-700 bg-gray-50 p-3 rounded-md">
                                {!! nl2br($encuesta->descripcion) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Estadísticas de Respuestas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Estadísticas</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total preguntas:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $encuesta->preguntas->count() }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Usuarios habilitados:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ $encuesta->capacitacion->invitaciones->filter(function($i){return $i->presente;})->count() }}
                            </span>
                        </div>
                        
                        @if($encuesta->estado != 0)
                        @php
                            $respondidas = 0;
                            foreach($encuesta->capacitacion->invitaciones->filter(function($i){return $i->presente;}) as $inv) {
                                if(!$encuesta->respondida_por($inv->usuario->id)->isEmpty()) $respondidas++;
                            }
                        @endphp
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Respondieron:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $respondidas }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Usuarios que pueden responder -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center mb-4">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Participantes</h3>
                    </div>

                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach ($encuesta->capacitacion->invitaciones->filter(function($i){return $i->presente;}) as $invitacionPresente)
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-blue-600">
                                        {{ substr($invitacionPresente->usuario->realname, 0, 2) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $invitacionPresente->usuario->realname }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Legajo: {{ $invitacionPresente->usuario->legajo }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if ($encuesta->estado != 0)
                                    @if ($encuesta->respondida_por($invitacionPresente->usuario->id)->isEmpty())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            Sin responder
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Respondida
                                        </span>    
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Preguntas y Respuestas -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900">Preguntas y Respuestas</h3>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ $encuesta->preguntas->count() }} preguntas
                            </span>
                            @if ($encuesta->estado == 0)
                                @can('Capacitaciones/Encuestas/Editar')
                                    @livewire('capacitaciones.encuesta.show.agregar-pregunta', ['encuesta' => $encuesta], key($encuesta->id.microtime(true)))
                                @endcan
                            @endif
                        </div>
                    </div>

                    <!-- Mensajes de estado -->
                    @if(session('success'))
                        <div class="mx-6 mt-4 p-3 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error') || $errors->any())
                        <div class="mx-6 mt-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-md">
                            @if(session('error'))
                                {{ session('error') }}
                            @endif
                            @if($errors->any())
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <div class="px-6 py-4 space-y-6">
                        @forelse ($encuesta->preguntas as $key => $pregunta)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                            {{ $key + 1 }}
                                        </span>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $pregunta->pregunta }}</h4>
                                    </div>
                                </div>
                                @if ($encuesta->estado == 0)
                                <div class="flex items-center space-x-2 ml-4">
                                    @can('Capacitaciones/Encuestas/Editar')
                                        @livewire('capacitaciones.encuesta.show.editar-pregunta', ['pregunta' => $pregunta], key($pregunta->id.microtime(true)))
                                        @livewire('capacitaciones.encuesta.show.eliminar-pregunta', ['pregunta' => $pregunta], key($pregunta->id.microtime(true)))
                                    @endcan
                                </div>
                                @endif
                            </div>

                            <div class="ml-8">
                                @if ($pregunta->con_opciones)
                                    <!-- Opciones de respuesta -->
                                    <div class="space-y-2">
                                        @foreach ($pregunta->opciones as $opcion)
                                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                                <span class="text-sm text-gray-700">{{ $opcion->opcion }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if ($encuesta->estado == 0)
                                                    @can('Capacitaciones/Encuestas/Editar')
                                                    <form action="{{ route('capacitaciones.opcions.destroy', $opcion) }}" method="post" class="inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-800 text-xs"
                                                                onclick="return confirm('¿Eliminar esta opción?')">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ \App\Models\Capacitaciones\Respuesta::where('opcion_id', $opcion->id)->count() }} votos
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                        @if ($encuesta->estado == 0)
                                        @can('Capacitaciones/Encuestas/Editar')
                                        <form action="{{ route('capacitaciones.opcions.store') }}" method="post" class="mt-3">
                                            @csrf
                                            <input type="hidden" name="pregunta_id" value="{{ $pregunta->id }}">
                                            <div class="flex space-x-2">
                                                <input type="text" 
                                                       name="opcion" 
                                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                       placeholder="Nueva opción...">
                                                <button type="submit" 
                                                        class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                                    + Agregar
                                                </button>
                                            </div>
                                        </form>
                                        @endcan
                                        @endif
                                    </div>
                                @else
                                    <!-- Pregunta de texto libre -->
                                    <div class="bg-gray-50 rounded-md p-4">
                                        @if ($encuesta->estado == 0)
                                            <div class="flex items-center text-gray-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <span class="text-sm italic">Esta pregunta es de texto libre</span>
                                            </div>
                                        @else
                                            <div class="space-y-3">
                                                @foreach(\App\Models\Capacitaciones\Respuesta::where('pregunta_id', $pregunta->id)->get() as $respuesta)
                                                <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                                    <div class="text-sm text-gray-700">{{ $respuesta->respuesta }}</div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay preguntas</h3>
                            <p class="text-gray-500">Agrega preguntas para comenzar a crear la encuesta.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>