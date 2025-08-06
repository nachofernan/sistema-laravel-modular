<div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Proveedores Invitados</h3>
            </div>
            @if (auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                <button class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-md transition-colors" wire:click="$set('open', true)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
                        <path d="M8 4a.75.75 0 0 1 .75.75v2.5h2.5a.75.75 0 0 1 0 1.5h-2.5v2.5a.75.75 0 0 1-1.5 0v-2.5h-2.5a.75.75 0 0 1 0-1.5h2.5v-2.5A.75.75 0 0 1 8 4Z" />
                    </svg>
                    Seleccionar
                </button>
            @endif
        </div>

        <div class="px-6 py-4">
            @forelse ($concurso->invitaciones as $invitacion)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                <a href="{{ route('proveedores.proveedors.show', $invitacion->proveedor) }}"
                                    class="hover:text-blue-600">
                                    {{ $invitacion->proveedor->razonsocial }}
                                </a>
                                <span class="text-gray-500 text-xs">{{ $invitacion->proveedor->cuit }}</span>
                            </div>
                            <div class="text-xs text-gray-500">
                                <a href="mailto:{{ $invitacion->proveedor->correo }}"
                                    class="hover:text-blue-600">{{ $invitacion->proveedor->correo }}</a>
                                <br>
                                <span class="font-medium text-red-600">
                                    {{ $invitacion->proveedor->estado->estado }}
                                </span>
                                @if ($invitacion->proveedor->falta_a_vencimiento() < 0)
                                    <span class="ml-2 text-red-600 font-bold text-xs">Doc.
                                        Vencida</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">
                                <strong>Invitación: </strong>
                                @switch($invitacion->intencion)
                                    @case(0)
                                        <span class="text-yellow-500">A la espera</span>
                                        @break
                                    @case(1)
                                        <span class="text-green-500">Con intención</span>
                                        @break
                                    @case(2)
                                        <span class="text-red-500">No participará</span>
                                        @break
                                    @case(3)
                                        <span class="text-green-500">Oferta Presentada</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                        <div class="flex items-center space-x-2">
                            @if ($concurso->estado->id == 1)
                                <button wire:click="quitarInvitacion({{ $invitacion->id }})"
                                    class="inline-flex items-center px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Quitar
                                </button>
                            @elseif ($invitacion->intencion == 0 || $invitacion->intencion == 2)
                                <button wire:click="reInvitar({{ $invitacion->id }})"
                                    class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors"
                                    onclick="invitacionEnviada{{ $invitacion->id }}(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Re-invitar
                                </button>
                                <script>
                                    function invitacionEnviada{{ $invitacion->id }}(button) {
                                        // Deshabilitar el botón
                                        button.disabled = true;
                                        // Cambiar texto del botón
                                        button.innerText = "Enviando...";
                                        // Cambiar estilo (opcional)
                                        button.classList.add("cursor-not-allowed");
                                    }
                                </script>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay proveedores invitados</h3>
                    <p class="text-gray-500">Los proveedores invitados aparecerán aquí.</p>
                </div>
            @endforelse
        </div>
    </div>

    <x-dialog-modal wire:model="open">
        <div class="max-w-10xl">
            <x-slot name="title">
                <div class="border-b py-2">
                    <div class="font-bold">
                        Editar Proveedores
                    </div>
                </div>
            </x-slot>
            <x-slot name="content">
                <div class="mb-4">
                    <input wire:model.debounce.300ms.live="search" type="text"
                        placeholder="Buscar por CUIT o Razón Social" class="w-full px-3 py-2 border rounded-md">
                </div>

                @if ($search != '')
                    <h3 class="text-lg font-semibold mb-2">Resultados de la búsqueda</h3>
                    @if (count($proveedoresBuscados) > 0)
                        <div class="mb-4">
                            @foreach ($proveedoresBuscados as $proveedor)
                                <div class="border-b py-2 flex justify-between items-center">
                                    <div>
                                        <strong>Razón Social:</strong> {{ $proveedor->razonsocial }}
                                        <br />
                                        <strong>CUIT:</strong> {{ $proveedor->cuit }} -
                                        {{ $proveedor->estado->estado }}
                                        @if ($proveedor->falta_a_vencimiento() < 0)
                                            <span class="ml-2 text-red-600 font-bold text-xs">Doc.
                                                Vencida</span>
                                        @endif
                                    </div>
                                    <div>
                                        <button wire:click="agregarInvitacion({{ $proveedor->id }})"
                                            onclick="handleButtonClick(this)"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">No se encontraron resultados para su búsqueda.</p>
                    @endif
                @else
                    @if (count($proveedoresRecomendados) > 0)
                        <h3 class="text-lg font-semibold mb-2">Proveedores Recomendados Por Rubro-Subrubro</h3>
                        <div class="mb-4">
                            @foreach ($proveedoresRecomendados as $proveedor)
                                <div class="border-b py-2 flex justify-between items-center">
                                    <div>
                                        <strong>Razón Social:</strong> {{ $proveedor->razonsocial }}
                                        <br />
                                        <strong>CUIT:</strong> {{ $proveedor->cuit }} -
                                        {{ $proveedor->estado->estado }}
                                        @if ($proveedor->falta_a_vencimiento() < 0)
                                        <span class="ml-2 text-red-600 font-bold text-xs">Doc.
                                                Vencida</span>
                                        @endif
                                    </div>
                                    <div>
                                        <button wire:click="agregarInvitacion({{ $proveedor->id }})"
                                            onclick="handleButtonClick(this)"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h3 class="text-center italic mb-2">No quedan proveedores dentro del subrubro para asociar</h3>
                    @endif
                @endif
                <script>
                    function handleButtonClick(button) {
                        // Deshabilitar el botón
                        button.disabled = true;
                        // Cambiar texto del botón
                        button.innerText = "Agregando...";
                        // Cambiar estilo (opcional)
                        button.classList.add("cursor-not-allowed", "opacity-50");
                    }
                </script>
            </x-slot>
            <x-slot name="footer">
            </x-slot>
        </div>
    </x-dialog-modal>
</div>
