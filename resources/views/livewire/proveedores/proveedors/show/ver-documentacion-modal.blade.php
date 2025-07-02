<div>
    {{-- Do your work, then step back. --}}
    <button class="hover:underline text-blue-600 text-sm" wire:click="$set('open', true)"> 
        Entrar
    </button> 
    <x-dialog-modal wire:model="open"> 
        <x-slot name="title"> 
            {{ $documentoTipo->codigo }} - {{ $documentoTipo->nombre }}
        </x-slot> 
        <x-slot name="content">
            <p class="text-sm">
                Documentación del proveedor {{ $proveedor->razonsocial }}.
            </p>
            <table class="min-w-full divide-y divide-gray-200 text-left my-4">
                <thead>
                    <tr class="py-2">
                        <th>Documento</th>
                        <th>Fecha de Carga</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $documento_actual = $documentoTipo->documentosProveedor($proveedor)->orderBy('id', 'desc')->first() @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-2">
                                <a href="{{Storage::disk('proveedores')->url($documento_actual->file_storage)}}" class="text-blue-600 hover:underline" target="_blank">Descargar</a>
                            </td>
                            <td class="py-2">{{ $documento_actual->created_at->format('d-m-Y') }}</td>
                            <td class="flex items-center gap-2 py-2">
                                <span>
                                    {{ $documento_actual->vencimiento ? $documento_actual->vencimiento->format('d-m-Y') : 'Sin Vencimiento' }}
                                </span>
                                @if ($documento_actual->vencimiento)
                                    @if (now()->addYear() > $documento_actual->vencimiento)
                                        @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::create($documento_actual->vencimiento), false) < 0)
                                            <span class="text-red-500"
                                            title="Documentación Vencida">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                </svg>                                                  
                                            </span>
                                        @else
                                            @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::create($documento_actual->vencimiento), false) < 30)
                                            <span class="text-yellow-500"
                                            title="Documentación a Vencer">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                            </svg>                                                  
                                            </span>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                                @if ($documento_actual->documentoTipo->vencimiento)
                                    @can('Proveedores/Proveedores/Editar')
                                        @livewire('proveedores.proveedors.show.update-vencimiento', ['documento' => $documento_actual], key($documento_actual->id . microtime(true)))
                                    @endcan
                                @endif
                            </td>
                            <td class="py-2">
                                @can('Proveedores/Proveedores/Editar')
                                    @livewire('proveedores.proveedors.show.borrar-documento', ['documento' => $documento_actual], key($documento_actual->id.microtime(true)))
                                @endcan
                            </td>
                        </tr>
                </tbody>
            </table>
            <p class="text-sm mt-3 border-t-4 pt-4">
                Antigua documentación
            </p>
            <table class="min-w-full divide-y divide-gray-200 text-left my-4">
                <thead>
                    <tr class="py-2">
                        <th>Documento</th>
                        <th>Fecha de Carga</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 border-t-4 mt-4">
                    @foreach ($documentoTipo->documentosProveedor($proveedor)->orderBy('id', 'desc')->get() as $documento)
                        @if ($loop->first)
                            @continue
                        @endif
                        <tr class="hover:bg-gray-50">
                            <td class="py-2">
                                <a href="{{Storage::disk('proveedores')->url($documento->file_storage)}}" class="text-blue-600 hover:underline" target="_blank">Descargar</a>
                            </td>
                            <td class="py-2">{{ $documento->created_at->format('d-m-Y') }}</td>
                            <td class="flex items-center gap-2 py-2">
                                <span>
                                    {{ $documento->vencimiento ? $documento->vencimiento->format('d-m-Y') : 'Sin Vencimiento' }}
                                </span>
                                @if ($documento->vencimiento)
                                    @if (now()->addYear() > $documento->vencimiento)
                                        @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::create($documento->vencimiento), false) < 0)
                                            <span class="text-red-500"
                                            title="Documentación Vencida">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                    <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                                </svg>                                                  
                                            </span>
                                        @else
                                            @if (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::create($documento->vencimiento), false) < 30)
                                            <span class="text-yellow-500"
                                            title="Documentación a Vencer">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                <path fill-rule="evenodd" d="M6.701 2.25c.577-1 2.02-1 2.598 0l5.196 9a1.5 1.5 0 0 1-1.299 2.25H2.804a1.5 1.5 0 0 1-1.3-2.25l5.197-9ZM8 4a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0v-3A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                                            </svg>                                                  
                                            </span>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                                @if ($documento->documentoTipo->vencimiento)
                                    @can('Proveedores/Proveedores/Editar')
                                        @livewire('proveedores.proveedors.show.update-vencimiento', ['documento' => $documento], key($documento->id . microtime(true)))
                                    @endcan
                                @endif
                            </td>
                            <td class="py-2">
                                @can('Proveedores/Proveedores/Editar')
                                    @livewire('proveedores.proveedors.show.borrar-documento', ['documento' => $documento], key($documento->id.microtime(true)))
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
    </x-dialog-modal> 
</div>
