<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <button class="text-blue-500 px-4 hover:underline font-medium text-sm" wire:click="$set('open', true)">
        Ver Oferta
    </button>
    <x-dialog-modal wire:model="open" class="bg-gray-50"> 
        <div class="max-w-4xl mx-auto">
            <x-slot name="title"> 
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center"> 
                    <h2 class="text-xl font-semibold">
                        Oferta de {{$invitacion->proveedor->razonsocial}}
                    </h2>
                    <button 
                        wire:click="descargarTodosDocumentos()" 
                        class="bg-white text-blue-600 px-3 py-2 rounded hover:bg-blue-50 transition flex items-center"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Descargar Todos
                    </button>
                </div>    
            </x-slot> 
            <x-slot name="content" class="p-6">
                @foreach ($concurso->documentos_requeridos as $documento_tipo)
                <div class="bg-white shadow-sm rounded-lg mb-1 overflow-hidden">
                    <div class="px-6 py-2 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{$documento_tipo->nombre}} 
                        </h3>
                    </div>
                    
                    @if ($documento_tipo->tipo_documento_proveedor)
                    <div class="px-6 py-2 flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            <span>Asociado a: {{$documento_tipo->tipo_documento_proveedor->nombre}}</span>
                            @php
                                $proveedorDocumento = $invitacion->proveedor->traer_documento_valido($documento_tipo->tipo_documento_proveedor->id, $concurso->fecha_cierre);
                            @endphp
                            @if ($proveedorDocumento)
                                @if ($proveedorDocumento->esValido($concurso->fecha_cierre))
                                    <span class="ml-2 px-2 py-1 bg-green-500 text-white rounded-full text-xs">Válido</span>
                                @else
                                    <span class="ml-2 px-2 py-1 bg-red-500 text-white rounded-full text-xs">No válido</span>
                                @endif
                            @else
                                <span class="ml-2 px-2 py-1 bg-gray-300 text-gray-700 rounded-full text-xs">No presentado</span>
                            @endif
                        </div>
                        @if ($proveedorDocumento)
                            <a 
                            href="{{Storage::disk('proveedores')->url($proveedorDocumento->file_storage)}}" 
                            class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center" 
                            target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Descargar
                            </a>
                        @endif
                    </div>
                    @endif
                    
                    <div class="px-6 py-2">
                        @foreach ($invitacion->documentos_con_tipo_id($documento_tipo->id) as $documento)
                            @if (method_exists($documento, 'esValidoParaOferta') && $documento->esValidoParaOferta($concurso->fecha_cierre))
                            <div class="flex justify-between items-center py-2 border-t border-gray-100">
                                <div class="text-sm text-gray-600">
                                    Cargado {{$documento->created_at->format('d-m-Y H:i')}}
                                    <span class="ml-2 px-2 py-1 bg-green-500 text-white rounded-full text-xs">Válido</span>
                                </div>
                                <a 
                                href="{{Storage::disk('concursos')->url($documento->file_storage)}}" 
                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center" 
                                target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
                @if (count($invitacion->documentos_sin_tipo_id()) > 0)
                <div class="bg-white shadow-sm rounded-lg mb-1 overflow-hidden">
                    <div class="px-6 py-2 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            Otros Documentos
                        </h3>
                    </div>
                    
                    <div class="px-6 py-2">
                        @foreach ($invitacion->documentos_sin_tipo_id() as $documento)
                            @if (method_exists($documento, 'esValidoParaOferta') && $documento->esValidoParaOferta($concurso->fecha_cierre))
                            <div class="flex justify-between items-center py-2 border-t border-gray-100">
                                <div class="text-sm text-gray-600">
                                    Cargado {{$documento->created_at->format('d-m-Y H:i')}}
                                    <span class="ml-2 px-2 py-1 bg-green-500 text-white rounded-full text-xs">Válido</span>
                                </div>
                                <a 
                                href="{{Storage::disk('concursos')->url($documento->file_storage)}}" 
                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center" 
                                target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar
                                </a>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
                @if (count($invitacion->documentos_post_concurso()) > 0)
                <div class="bg-white shadow-sm rounded-lg mb-1 overflow-hidden">
                    <div class="px-6 py-2 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            Otros Documentos Post-Apertura
                        </h3>
                    </div>
                    
                    <div class="px-6 py-2">
                        @foreach ($invitacion->documentos_post_concurso() as $documento)
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <div class="text-sm text-gray-600">
                                Cargado {{$documento->created_at->format('d-m-Y H:i')}}
                                @if ($documento->user_id_created)
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium ml-2 px-2 py-0.5 rounded">BAESA</span>
                                @endif
                            </div>
                            <a 
                            href="{{Storage::disk('concursos')->url($documento->file_storage)}}" 
                            class="text-blue-600 hover:text-blue-800 transition-colors duration-200 flex items-center" 
                            target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Descargar
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @if((auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id()) && $concurso->estado_id == 3)
                <div class="bg-white shadow-sm rounded-lg mb-1 overflow-hidden">
                    <div class="px-6 py-2 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-medium text-gray-900">
                            Cargar Documento BAESA
                        </h3>
                    </div>
                    
                    <div class="px-6 py-4">
                        <form action="{{ route('concursos.documentos.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="invitacion_id" value="{{ $invitacion->id }}">
                            
                            <div class="mb-3">
                                <input type="file" name="file" id="file" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            </div>
                            
                            <div class="text-right">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Cargar Documento
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </x-slot> 
            <x-slot name="footer">
                <!-- Puedes agregar un botón de cierre o acciones adicionales si lo deseas -->
            </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
