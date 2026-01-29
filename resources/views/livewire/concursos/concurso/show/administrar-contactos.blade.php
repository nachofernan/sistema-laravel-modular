<div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2v-6a2 2 0 012-2zM7 8H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Contactos</h3>
            </div>
            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                    <button 
                        wire:click="$set('open_nuevo', true)" 
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
                            <path d="M8 4a.75.75 0 0 1 .75.75v2.5h2.5a.75.75 0 0 1 0 1.5h-2.5v2.5a.75.75 0 0 1-1.5 0v-2.5h-2.5a.75.75 0 0 1 0-1.5h2.5v-2.5A.75.75 0 0 1 8 4Z" />
                        </svg>
                        Agregar contacto
                    </button>
                @endif
            @endif
        </div>

        <div class="px-6 py-4">
            @forelse ($concurso->contactos as $contacto)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $contacto->nombre }}</div>
                            <div class="text-xs text-gray-500">{{ $contacto->correo }}</div>
                            <div class="text-xs text-gray-500">{{ $contacto->telefono }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $contacto->tipo == 'administrativo' ? 'Administrativo' : 'Técnico' }}
                            </div>
                        </div>
                    </div>
                    @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                        @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                        <div class="flex space-x-2">
                            <button 
                                wire:click="abrirYeditar({{$contacto->id}})"
                                class="inline-flex items-center px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </button>
                            <button 
                                onclick="confirmDelete({{$contacto->id}})"
                                class="inline-flex items-center px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>

                            <script>
                                function confirmDelete(contactoId) {
                                    if (confirm('¿Estás seguro de que quieres eliminar este contacto?')) {
                                        Livewire.dispatch('eliminarContacto', { encargado_id: contactoId });
                                    }
                                }
                            </script>
                        </div>
                        @endif
                    @endif
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2v-6a2 2 0 012-2zM7 8H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v-6a2 2 0 00-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay contactos</h3>
                    <p class="text-gray-500">Los contactos del concurso aparecerán aquí.</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <x-dialog-modal wire:model="open_nuevo"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2 grid grid-cols-2"> 
                <div class="font-bold col mt-1">
                    Nuevo Contacto
                </div>
            </div>          
        </x-slot> 
        <x-slot name="content">
            <div class="grid-datos-show">
                <div class="atributo-edit">
                    Nombre
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="nombre" class="input-full">
                </div>
                <div class="atributo-edit">
                    Correo Electrónico
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="correo" class="input-full">
                </div>
                <div class="atributo-edit">
                    Teléfono
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="telefono" class="input-full">
                </div>
                <div class="atributo-edit">
                    Tipo de Contacto
                </div>
                <div class="valor-edit">
                    <select wire:model="tipo" class="input-full">
                        <option value="administrativo">Administrativo</option>
                        <option value="tecnico">Técnico</option>
                    </select>
                </div>
                
                <div class="atributo-edit">
                </div>
                <div class="valor-edit text-right">
                    <button class="boton-celeste text-sm" wire:click="guardar()">Guardar</button>
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 

    <x-dialog-modal wire:model="open_edit"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2 grid grid-cols-2"> 
                <div class="font-bold col mt-1">
                    Editar Contacto
                </div>
            </div>          
        </x-slot> 
        <x-slot name="content">
            <div class="grid-datos-show">
                <div class="atributo-edit">
                    Nombre
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="nombre_edit" class="input-full">
                </div>
                <div class="atributo-edit">
                    Correo Electrónico
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="correo_edit" class="input-full">
                </div>
                <div class="atributo-edit">
                    Teléfono
                </div>
                <div class="valor-edit">
                    <input type="text" wire:model="telefono_edit" class="input-full">
                </div>
                <div class="atributo-edit">
                    Tipo de Contacto
                </div>
                <div class="valor-edit">
                    <select wire:model="tipo_edit" class="input-full">
                        <option value="administrativo" @selected($tipo_edit == 'administrativo')>Administrativo</option>
                        <option value="tecnico" @selected($tipo_edit == 'tecnico')>Técnico</option>
                    </select>
                </div>
                
                <div class="atributo-edit">
                </div>
                <div class="valor-edit text-right">
                    <button class="boton-celeste text-sm" wire:click="actualizar()">Guardar</button>
                </div>
            </div>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
