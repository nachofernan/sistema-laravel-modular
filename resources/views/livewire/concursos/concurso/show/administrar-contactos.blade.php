<div>
    {{-- Stop trying to control. --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 mt-4">
        <div class="bg-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-700">Contactos</h2>
            @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
            @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
            <button 
                wire:click="$set('open_nuevo', true)" 
                class="px-4 py-2 text-blue-600 text-sm hover:underline"
            >
                Agregar contacto
            </button>
            @endif
            @endif
        </div>
    
        <div class="divide-y divide-gray-200">
            @forelse ($concurso->contactos as $contacto)
                <div class="px-6 py-2 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <div class="mt-2 space-y-1 text-gray-700">
                                <div class="flex items-center">
                                    <span class="mr-3 font-medium text-gray-600">{{ $contacto->nombre }}</span>
                                    <span class="text-xs">
                                        {{ $contacto->tipo == 'administrativo' ? 'Administrativo' : 'Técnico' }}
                                    </span>
                                </div>
                                <div class="text-sm flex items-center">
                                    <span class="mr-3 font-medium text-gray-600">{{ $contacto->correo }}</span>
                                    <span>{{ $contacto->telefono }}</span>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->can('Concursos/Concursos/Editar') || $concurso->user_id === auth()->id())
                        @if ($concurso->estado->id < 3 && $concurso->fecha_cierre > now())
                        <button 
                            wire:click="abrirYeditar({{$contacto->id}})"
                            class="ml-4 text-blue-500 hover:text-blue-700 hover:bg-blue-50 px-3 py-2 rounded-md transition-colors flex items-center text-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </button>
                        @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-4 text-center text-gray-500">
                    No hay contactos registrados
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
