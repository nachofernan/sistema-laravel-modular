<x-app-layout>
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded-lg overflow-hidden" role="article">
            <div class="flex justify-between border-b text-white font-bold bg-gradient-to-r p-6
            @switch($concurso->estado->id)
                @case(1)
                from-orange-700 to-orange-400
                    @break
                @case(2)
                @if ($concurso->fecha_cierre > now())
                    from-green-700 to-green-400
                @else
                    from-yellow-700 to-yellow-400
                @endif
                    @break
                @case(3)
                from-blue-700 to-blue-400
                    @break
                @case(4)
                from-blue-700 to-blue-400
                    @break
                @case(5)
                from-red-700 to-red-400
                    @break
            @endswitch
            " role="banner">
                <div>
                    <h1 class="text-2xl font-bold text-white" id="concurso-nombre">
                        {{ $concurso->nombre }}
                    </h1>
                    <div class="font-normal" aria-label="Estado del concurso">
                        #{{ $concurso->numero ?? 'Sin Número' }} - 
                        @switch($concurso->estado->id)
                            @case(2)
                            @if ($concurso->fecha_cierre > now())
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cerrado
                                </span>
                            @endif
                                @break
                            @default
                                {{ $concurso->estado->nombre }}
                        @endswitch
                    </div>
                </div>
                <div class="text-sm flex justify-end gap-4 items-center">
                    <a href="{{route('concursos.pdfbb', $concurso)}}" 
                       class="bg-white hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded flex items-center transition-colors duration-200"
                       target="_blank"
                       aria-label="Descargar PDF del concurso"
                       title="Descargar PDF del concurso">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 mr-1" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4 2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5V6.621a1.5 1.5 0 0 0-.44-1.06L9.94 2.439A1.5 1.5 0 0 0 8.878 2H4Zm4 3.5a.75.75 0 0 1 .75.75v2.69l.72-.72a.75.75 0 1 1 1.06 1.06l-2 2a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l.72.72V6.25A.75.75 0 0 1 8 5.5Z" clip-rule="evenodd" />
                        </svg>                          
                        Descargar PDF
                    </a>
                    @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                        @if ($concurso->estado->id < 4)
                            @livewire('concursos.concurso.show.acciones-concurso', ['concurso' => $concurso], key($concurso->id))
                        @endif
                    @endif
                </div>
            </div>
            <div class="block w-full overflow-x-auto py-5 px-5">
                <div class="grid grid-cols-2 gap-5">
                    <div class="col">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h2 class="font-medium text-gray-700" id="datos-generales">
                                    Datos Generales
                                </h2>
                                @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                    @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                                        <div>
                                            @livewire('concursos.concurso.show.edit-concurso', ['concurso' => $concurso], key($concurso->id))
                                        </div>
                                    @endif
                                @endif
                            </div>
                        
                            <div class="p-6">
                                <dl class="space-y-4 text-sm" aria-labelledby="datos-generales">
                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Nombre</dt>
                                        <dd class="text-gray-800">{{ $concurso->nombre }}</dd>
                                    </div>

                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Número</dt>
                                        <dd class="text-gray-800">#{{ $concurso->numero ?? 'Sin Número' }}</dd>
                                    </div>
                        
                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Descripción</dt>
                                        <dd class="text-gray-800 text-right">{{ $concurso->descripcion }}</dd>
                                    </div>
                        
                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Legajo Electrónico</dt>
                                        <dd class="flex items-center">
                                            <a href="{{$concurso->legajo}}" 
                                               target="_blank" 
                                               title="Ver Legajo"
                                               class="text-blue-600 hover:underline flex items-center transition-colors duration-200"
                                               aria-label="Ver legajo electrónico">
                                               {{$concurso->numero_legajo}}
                                               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 ml-1 pb-1" aria-hidden="true">
                                                <path d="M6.22 8.72a.75.75 0 0 0 1.06 1.06l5.22-5.22v1.69a.75.75 0 0 0 1.5 0v-3.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0 0 1.5h1.69L6.22 8.72Z" />
                                                <path d="M3.5 6.75c0-.69.56-1.25 1.25-1.25H7A.75.75 0 0 0 7 4H4.75A2.75 2.75 0 0 0 2 6.75v4.5A2.75 2.75 0 0 0 4.75 14h4.5A2.75 2.75 0 0 0 12 11.25V9a.75.75 0 0 0-1.5 0v2.25c0 .69-.56 1.25-1.25 1.25h-4.5c-.69 0-1.25-.56-1.25-1.25v-4.5Z" />
                                              </svg>                                              
                                            </a>
                                        </dd>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Usuario Gestor</dt>
                                        <dd class="text-gray-800">{{ $concurso->usuario->realname ?? 'Sin gestor' }}</dd>
                                    </div>
                        
                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Sedes</dt>
                                        <dd class="text-right">
                                            @foreach ($concurso->sedes as $sede)
                                                <div>{{ $sede->nombre }}</div>
                                            @endforeach
                                        </dd>
                                    </div>
                        
                                    <div class="flex justify-between border-b pb-2">
                                        <dt class="font-medium text-gray-600">Fecha Inicio</dt>
                                        <dd class="text-gray-800">{{ $concurso->fecha_inicio->format('d-m-Y - H:i') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        
                            <div class="bg-gray-50 px-2 py-4">
                                    <div class="flex justify-between items-center px-4 ">
                                        <span class="font-medium text-gray-600">Fecha Cierre</span>
                                        <span class="text-gray-800 font-bold">{{ $concurso->fecha_cierre->format('d-m-Y - H:i') }}</span>
                                        @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                            @if ($concurso->estado->id == 2 && $concurso->fecha_cierre > now())
                                                @livewire('concursos.concurso.show.create-prorroga', ['concurso' => $concurso], key($concurso->id))
                                            @endif
                                        @endif
                                    </div>
                        
                                @if (count($concurso->prorrogas) > 0)
                                    <div class="mt-4 space-y-2 bg-white rounded-md shadow-sm py-2 text-xs">
                                        @foreach ($concurso->prorrogas as $key => $prorroga)
                                            <div class="flex justify-between items-center px-4">
                                                <span class="font-medium">Prórroga {{ $key+1 }}</span>
                                                <div class="text-xs text-gray-600">
                                                    {{ $prorroga->fecha_anterior->format('d-m-Y - H:i') }} 
                                                    <span class="mx-2">➔</span> 
                                                    {{ $prorroga->fecha_actual->format('d-m-Y - H:i') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 p-4 text-lg flex justify-between items-center">
                                <h3 class="font-medium text-gray-700">
                                    Historial de Estados
                                </h3>
                            </div>
                        
                            <div class="p-6">
                                <div class="space-y-4 text-sm">
                                    @foreach ($concurso->historial as $historial)
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="font-medium text-gray-600">
                                            {{ $historial->estado->nombre }}
                                        </span>
                                        <span class="text-gray-800">
                                            {{ $historial->created_at->format('d-m-Y - H:i') }}
                                        </span>
                                    </div>
                                    @if ($historial->estado->id == 2 && $concurso->fecha_cierre < now())
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="font-medium text-gray-600">
                                            Cerrado por Fecha
                                        </span>
                                        <span class="text-gray-800">
                                            {{ $concurso->fecha_cierre->format('d-m-Y - H:i') }}
                                        </span>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @livewire('concursos.concurso.show.administrar-contactos', ['concurso' => $concurso])
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 mt-4">
                            <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
                                <h2 class="text-lg font-medium text-gray-700" id="documentacion-concurso">
                                    Documentación Adjunta del Concurso
                                </h2>
                                @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                    @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                                        @livewire('concursos.concurso.show.create-documento', ['concurso' => $concurso], key($concurso->id))
                                    @endif
                                @endif
                            </div>
                        
                            <div class="divide-y divide-gray-200" role="list" aria-labelledby="documentacion-concurso">
                                @forelse ($concurso->documentos as $documento)
                                    <div class="px-6 py-2 hover:bg-gray-50 transition-colors duration-200" role="listitem">
                                        <div class="flex justify-between items-center">
                                            <span class="mr-3 font-medium text-gray-600">{{$documento->documentoTipo->nombre}}</span>
                                            <span class="flex justify-between items-center">
                                                <a 
                                                    href="{{ route('concursos.documentos.show', $documento) }}" 
                                                    target="_blank"
                                                    class="ml-4 text-blue-500 hover:text-blue-700 hover:bg-blue-50 px-3 py-2 rounded-md transition-colors duration-200 text-sm"
                                                    aria-label="Descargar documento {{$documento->documentoTipo->nombre}}"
                                                    title="Descargar documento"
                                                >                                            
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 mr-1" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M4 2a1.5 1.5 0 0 0-1.5 1.5v9A1.5 1.5 0 0 0 4 14h8a1.5 1.5 0 0 0 1.5-1.5V6.621a1.5 1.5 0 0 0-.44-1.06L9.94 2.439A1.5 1.5 0 0 0 8.878 2H4Zm4 3.5a.75.75 0 0 1 .75.75v2.69l.72-.72a.75.75 0 1 1 1.06 1.06l-2 2a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 0 1 1.06-1.06l.72.72V6.25A.75.75 0 0 1 8 5.5Z" clip-rule="evenodd" />
                                                        </svg>
                                                        Descargar
                                                    </span>
                                                </a>
                                                @if ($concurso->estado->id == 1)
                                                    <form action="{{ route('concursos.documentos.destroy', $documento) }}" method="post" class="inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button 
                                                            type="submit" 
                                                            onclick="return confirm('¿Está seguro que desea eliminar el documento?')"
                                                            class="ml-4 text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-2 rounded-md transition-colors duration-200 text-sm"
                                                            aria-label="Eliminar documento {{$documento->documentoTipo->nombre}}"
                                                            title="Eliminar documento"
                                                        >
                                                            <span class="flex items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 mr-1" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M5 3.25V4H2.75a.75.75 0 0 0 0 1.5H14v1.5H2.75a.75.75 0 0 0 0 1.5H5v.75A2.75 2.75 0 0 0 7.75 11h.5A2.75 2.75 0 0 0 11 8.25v-.75h2.25a.75.75 0 0 0 0-1.5H11v-1.5h2.25a.75.75 0 0 0 0-1.5H11v-.75A2.75 2.75 0 0 0 8.25 2h-.5A2.75 2.75 0 0 0 5 4.75ZM8.25 3.5a1.25 1.25 0 0 1 1.25 1.25v4.5a1.25 1.25 0 0 1-1.25 1.25h-.5A1.25 1.25 0 0 1 6.5 9.25v-4.5A1.25 1.25 0 0 1 7.75 3.5h.5Z" clip-rule="evenodd" />
                                                                </svg>
                                                                Eliminar
                                                            </span>
                                                        </button>    
                                                    </form>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-6 py-4 text-center text-gray-500" role="status">
                                        Sin documentación
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @livewire('concursos.concurso.show.require-documentos', ['concurso' => $concurso], key($concurso->id))
                    </div>
                    <div class="col">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
                                <h2 class="text-lg font-medium text-gray-700">Rubro y Subrubro</h2>
                                @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                                    @livewire('concursos.concurso.rubros', ['concurso' => $concurso], key($concurso->id))
                                @endif
                                @endif
                            </div>
                        
                            <div class="p-6">
                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="font-medium text-gray-600">Rubro</span>
                                        <span class="text-gray-800">{{ $concurso->subrubro ? $concurso->subrubro->rubro->nombre : 'Sin Rubro' }}</span>
                                    </div>
                        
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="font-medium text-gray-600">Subrubro</span>
                                        <span class="text-gray-800">{{ $concurso->subrubro ? $concurso->subrubro->nombre : 'Sin Subrubro' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($concurso->estado->id == 3)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                            <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
                                <h2 class="text-lg font-medium text-gray-700">Cargar Documentación Post-Apertura</h2>
                                
                            </div>
                        
                            <div class="p-6">
                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-600">
                                            @if ($concurso->permite_carga)
                                            <span class="bg-green-600 px-4 py-1 text-white rounded">Habilitado</span>
                                            @else
                                            <span class="bg-red-600 px-4 py-1 text-white rounded">Deshabilitado</span>
                                            @endif
                                        </span>
                                        <span class="text-gray-800">
                                            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                            @if ($concurso->permite_carga)
                                            <form action="{{ route('concursos.concursos.update', $concurso) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="permite_carga" value="0">
                                                <button type="submit" class="text-red-600 hover:underline ">Deshabilitar</button>
                                            </form>
                                            @else
                                            <form action="{{ route('concursos.concursos.update', $concurso) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <input type="hidden" name="permite_carga" value="1">
                                                <button type="submit" class="text-blue-600 hover:underline ">Habilitar</button>
                                            </form>
                                            @endif
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                            @livewire('concursos.concurso.invitar-proveedor', ['concurso' => $concurso], key($concurso->id))
                        @else
                            <div class="subtitulo-show pt-4">
                                Proveedores con Ofertas Presentadas
                            </div>
                            <div class="grid grid-cols-12 gap-x-4">
                                <div class="col-span-12 space-y-4">
                                    @foreach ($concurso->invitaciones->where('intencion', 3) as $invitacion)
                                        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-800">
                                                        <a href="{{ route('proveedores.proveedors.show', $invitacion->proveedor) }}" class="hover:text-blue-600 hover:underline">
                                                            {{ $invitacion->proveedor->razonsocial }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        <strong>CUIT:</strong> {{ $invitacion->proveedor->cuit }} - 
                                                        <span class="font-medium text-red-600">
                                                            {{ $invitacion->proveedor->estado->estado }}
                                                        </span>
                                                    </p>
                                                </div>
                                                {{-- <button class="text-blue-500 px-4 hover:underline font-medium text-sm">
                                                    Ver Oferta
                                                </button> --}}
                                                @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                                                @if ($concurso->estado->id == 3 || $concurso->estado->id == 4)
                                                @livewire('concursos.concurso.show.ver-oferta', ['concurso' => $concurso, 'invitacion' => $invitacion], key($invitacion->id))
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="subtitulo-show pt-4">
                                Otros Proveedores Invitados
                            </div>
                            <div class="grid grid-cols-12 gap-x-4">
                                <div class="col-span-12 space-y-4">
                                    @foreach ($concurso->invitaciones->filter(function ($invitacion) {
                                        return $invitacion->intencion != 3;
                                    }) as $invitacion)
                                        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-800">
                                                        <a href="{{ route('proveedores.proveedors.show', $invitacion->proveedor) }}" class="hover:text-blue-600 hover:underline">
                                                            {{ $invitacion->proveedor->razonsocial }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500">
                                                        <strong>CUIT:</strong> {{ $invitacion->proveedor->cuit }} - 
                                                        <span class="font-medium text-red-600">
                                                            {{ $invitacion->proveedor->estado->estado }}
                                                        </span>
                                                    </p>
                                                </div>
                                                @switch($invitacion->intencion)
                                                    @case(0)
                                                        <span class="text-orange-500 px-4 font-medium text-sm">Nunca contestó</span>
                                                        @break
                                                    @case(1)
                                                        <span class="text-yellow-500 px-4 font-medium text-sm">Hubo intención</span>
                                                        @break
                                                    @case(2)
                                                        <span class="text-red-800 px-4 font-medium text-sm">Hubo negativa</span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
