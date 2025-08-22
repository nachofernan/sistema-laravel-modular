<div>
    <button class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors" wire:click="$set('open', true)"> 
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        Ver más
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            <div class="flex items-center">
                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ $documentoTipo->codigo }} - {{ $documentoTipo->nombre }}
            </div>
        </x-slot> 
        <x-slot name="content">
            <p class="text-sm text-gray-600 mb-4">
                Documentación del proveedor <strong>{{ $proveedor->razonsocial }}</strong>.
            </p>
            
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Documento Actual</h4>
                    @php $documento_actual = $documentoTipo->documentosProveedor($proveedor)->orderBy('id', 'desc')->first() @endphp
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <a href="{{ route('proveedores.documentos.show', $documento_actual) }}" class="text-blue-600 hover:underline font-medium" target="_blank">Descargar</a>
                                    <div class="text-xs text-gray-500">Cargado: {{ $documento_actual->created_at->format('d-m-Y') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-600">
                                    Vencimiento: {{ $documento_actual->vencimiento ? $documento_actual->vencimiento->format('d-m-Y') : 'Sin Vencimiento' }}
                                </div>
                                @if ($documento_actual->vencimiento)
                                    @if (now()->addYear() > $documento_actual->vencimiento)
                                        @if ($documento_actual->vencimiento->isPast())
                                            <span class="text-red-500 text-xs" title="Documentación Vencida">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 inline">
                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                </svg> Vencido
                                            </span>
                                        @elseif ($documento_actual->vencimiento->subDays(30)->isPast())
                                            <span class="text-yellow-500 text-xs" title="Documentación a Vencer">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 inline">
                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                </svg> Por Vencer
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center justify-end space-x-2">
                            <div>
                                @if ($documento_actual->documentoTipo->vencimiento)
                                    @can('Proveedores/Proveedores/Editar')
                                        <div class="mt-2">
                                            @livewire('proveedores.proveedors.show.update-vencimiento', ['documento' => $documento_actual], key($documento_actual->id . microtime(true)))
                                        </div>
                                    @endcan
                                @endif
                            </div>
                            <div>
                                @can('Proveedores/Proveedores/Editar')
                                    <div class="mt-2">
                                        @livewire('proveedores.proveedors.show.borrar-documento', ['documento' => $documento_actual], key($documento_actual->id.microtime(true)))
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                
                @php $documentos_antiguos = $documentoTipo->documentosProveedor($proveedor)->orderBy('id', 'desc')->get() @endphp
                @if ($documentos_antiguos->count() > 1)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Documentación Anterior</h4>
                        <div class="space-y-2">
                            @foreach ($documentos_antiguos as $documento)
                                @if ($loop->first)
                                    @continue
                                @endif
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <a href="{{ route('proveedores.documentos.show', $documento) }}" class="text-blue-600 hover:underline text-sm" target="_blank">Descargar</a>
                                                <div class="text-xs text-gray-500">Cargado: {{ $documento->created_at->format('d-m-Y') }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-600">
                                                Vencimiento: {{ $documento->vencimiento ? $documento->vencimiento->format('d-m-Y') : 'Sin Vencimiento' }}
                                            </div>
                                            @if ($documento->vencimiento)
                                                @if (now()->addYear() > $documento->vencimiento)
                                                    @if ($documento->vencimiento->isPast())
                                                        <span class="text-red-500 text-xs" title="Documentación Vencida">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 inline">
                                                                <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                            </svg> Vencido
                                                        </span>
                                                    @elseif ($documento->vencimiento->subDays(30)->isPast())
                                                        <span class="text-yellow-500 text-xs" title="Documentación a Vencer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-3 h-3 inline">
                                                                <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                            </svg> Por Vencer
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end space-x-2">
                                        <div>
                                        @if ($documento->documentoTipo->vencimiento)
                                            @can('Proveedores/Proveedores/Editar')
                                                <div class="mt-2">
                                                    @livewire('proveedores.proveedors.show.update-vencimiento', ['documento' => $documento], key($documento->id . microtime(true)))
                                                </div>
                                            @endcan
                                        @endif
                                        </div>
                                        <div>
                                        @can('Proveedores/Proveedores/Editar')
                                            <div class="mt-2">
                                                @livewire('proveedores.proveedors.show.borrar-documento', ['documento' => $documento], key($documento->id.microtime(true)))
                                            </div>
                                        @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
