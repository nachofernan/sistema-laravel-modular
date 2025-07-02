<div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 mt-4">
        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Proveedores Invitados</h2>
            @if (auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                <button class="link-azul float-right text-sm mt-2 mr-4" wire:click="$set('open', true)">
                    Seleccionar
                </button>
            @endif
        </div>

        <div class="divide-y divide-gray-200">
            @forelse ($concurso->invitaciones as $invitacion)
                <div class="bg-white px-6 py-4">
                    <div class="flex justify-between items-center border-b pb-2 mb-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                <a href="{{ route('proveedores.proveedors.show', $invitacion->proveedor) }}"
                                    class="hover:text-blue-600 hover:underline">
                                    {{ $invitacion->proveedor->razonsocial }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $invitacion->proveedor->cuit }} -
                                <a href="mailto:{{ $invitacion->proveedor->correo }}"
                                    class="hover:text-blue-600 hover:underline">{{ $invitacion->proveedor->correo }}</a>
                                -
                                <span class="font-medium text-red-600">
                                    {{ $invitacion->proveedor->estado->estado }}
                                </span>
                                @if ($invitacion->proveedor->falta_a_vencimiento() < 0)
                                    - <span class="bg-red-600 px-2 font-bold text-xs rounded text-white">Doc.
                                        Vencida</span>
                                @endif
                            </p>
                        </div>
                        @if (auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                            @if ($concurso->estado->id == 1)
                                <button wire:click="quitarInvitacion({{ $invitacion->id }})"
                                    class="text-red-500 hover:underline font-medium text-sm">
                                    Quitar
                                </button>
                            @elseif ($invitacion->intencion == 0 || $invitacion->intencion == 2)
                                <button wire:click="reInvitar({{ $invitacion->id }})"
                                    class="text-blue-500 hover:underline font-medium text-sm"
                                    onclick="invitacionEnviada{{ $invitacion->id }}(this)">
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
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">
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
                        </p>
                    </div>
                </div>
                @empty
                    <div class="px-6 py-4 text-center text-gray-500">
                        No se han invitado proveedores aún
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
                                                - <span class="bg-red-600 px-2 font-bold text-xs rounded text-white">Doc.
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
                                                - <span class="bg-red-600 px-2 font-bold text-xs rounded text-white">Doc.
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
