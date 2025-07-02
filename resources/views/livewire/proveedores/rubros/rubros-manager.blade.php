<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <!-- Header con búsqueda -->
    <div class="grid grid-cols-10 py-2 mb-4">
        <div class="col-span-4 titulo-index">
            Administrar Rubros y Subrubros
        </div>
        <div class="col-span-4">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Buscar rubros o subrubros..." 
                   class="input-full">
        </div>
        <div class="col-span-2">
            <button wire:click="openCreateRubro" 
                    class="w-full boton-celeste">
                + Nuevo Rubro
            </button>
        </div>
    </div>

    <!-- Mensajes de feedback -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Lista de Rubros -->
    <div class="bg-white shadow-lg rounded overflow-hidden">
        @forelse($rubros as $rubro)
        <div class="border-b border-gray-200 hover:bg-gray-50">
            <!-- Rubro Header -->
            <div class="grid grid-cols-12 gap-3 px-4 py-3 items-center">
                <div class="col-span-1">
                    <button wire:click="toggleRubro({{ $rubro->id }})" 
                            class="text-gray-500 hover:text-gray-700 text-xs">
                        @if(in_array($rubro->id, $expandedRubros))
                            Cerrar
                        @else
                            Ver
                        @endif
                    </button>
                </div>
                <div class="col-span-6 font-bold text-lg">
                    {{ $rubro->nombre }}
                    <span class="text-sm text-gray-500 font-normal ml-2">
                        ({{ $rubro->subrubros->count() }} subrubros)
                    </span>
                </div>
                <div class="col-span-5 text-right space-x-2">
                    <button wire:click="openCreateSubrubro({{ $rubro->id }})" 
                            class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                        + Subrubro
                    </button>
                    <button wire:click="openEditRubro({{ $rubro->id }})" 
                            class="text-xs px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        Editar
                    </button>
                    <button wire:click="openDeleteRubro({{ $rubro->id }})" 
                            class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                        Eliminar
                    </button>
                </div>
            </div>

            <!-- Subrubros (expandible) -->
            @if(in_array($rubro->id, $expandedRubros) && $rubro->subrubros->count() > 0)
            <div class="bg-gray-50 px-4 pb-3">
                @foreach($rubro->subrubros as $subrubro)
                <div class="grid grid-cols-12 gap-3 py-2 pl-8 border-b border-gray-200 last:border-b-0">
                    <div class="col-span-7 text-sm">
                        {{ $subrubro->nombre }}
                    </div>
                    <div class="col-span-5 text-right space-x-2">
                        <button wire:click="openEditSubrubro({{ $subrubro->id }})" 
                                class="text-xs px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Editar
                        </button>
                        <button wire:click="openDeleteSubrubro({{ $subrubro->id }})" 
                                class="text-xs px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            Eliminar
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            @if(strlen($search) > 2)
                No se encontraron resultados para "{{ $search }}"
            @else
                No hay rubros creados aún
            @endif
        </div>
        @endforelse
    </div>

    <!-- Modal Universal -->
    <x-dialog-modal wire:model="modalOpen" maxWidth="2xl">
        <x-slot name="title">
            {{ $modalTitle }}
        </x-slot>

        <x-slot name="content">
            @if($modalType === 'create_rubro' || $modalType === 'edit_rubro' || $modalType === 'create_subrubro' || $modalType === 'edit_subrubro')
                <!-- Form para crear/editar -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre
                    </label>
                    <input wire:model="nombre" 
                           type="text" 
                           class="input-full @error('nombre') border-red-500 @enderror" 
                           placeholder="Ingrese el nombre...">
                    @error('nombre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            @elseif($modalType === 'delete_rubro')
                <!-- Confirmación eliminar rubro -->
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">¿Eliminar Rubro?</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Está a punto de eliminar el rubro <strong>"{{ $currentRubro->nombre ?? '' }}"</strong>.
                        @if($currentRubro && $currentRubro->subrubros->count() > 0)
                            <br><span class="text-red-600 font-medium">
                                Este rubro tiene {{ $currentRubro->subrubros->count() }} subrubros y no puede ser eliminado.
                            </span>
                        @else
                            <br>Esta acción no se puede deshacer.
                        @endif
                    </p>
                </div>

            @elseif($modalType === 'delete_subrubro')
                <!-- Confirmación eliminar subrubro -->
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">¿Eliminar Subrubro?</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Está a punto de eliminar el subrubro <strong>"{{ $currentSubrubro->nombre ?? '' }}"</strong> 
                        del rubro <strong>"{{ $currentSubrubro->rubro->nombre ?? '' }}"</strong>.
                        <br>Esta acción no se puede deshacer.
                    </p>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-end space-x-2">
                <button wire:click="closeModal" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Cancelar
                </button>

                @if($modalType === 'create_rubro')
                    <button wire:click="createRubro" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                            wire:loading.attr="disabled">
                        Crear Rubro
                    </button>
                @elseif($modalType === 'edit_rubro')
                    <button wire:click="updateRubro" 
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                            wire:loading.attr="disabled">
                        Actualizar Rubro
                    </button>
                @elseif($modalType === 'create_subrubro')
                    <button wire:click="createSubrubro" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                            wire:loading.attr="disabled">
                        Crear Subrubro
                    </button>
                @elseif($modalType === 'edit_subrubro')
                    <button wire:click="updateSubrubro" 
                            class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                            wire:loading.attr="disabled">
                        Actualizar Subrubro
                    </button>
                @elseif($modalType === 'delete_rubro')
                    @if($currentRubro && $currentRubro->subrubros->count() == 0)
                        <button wire:click="deleteRubro" 
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                                wire:loading.attr="disabled">
                            Eliminar Rubro
                        </button>
                    @endif
                @elseif($modalType === 'delete_subrubro')
                    <button wire:click="deleteSubrubro" 
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                            wire:loading.attr="disabled">
                        Eliminar Subrubro
                    </button>
                @endif
            </div>
        </x-slot>
    </x-dialog-modal>
</div>