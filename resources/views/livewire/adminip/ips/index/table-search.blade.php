<div>
    <!-- Header con título y acciones -->
    <x-page-header title="Administración de IPs">
        <x-slot:actions>
            @can('AdminIP/IPS/Crear')
                <button wire:click="$dispatch('openCreateModal')" 
                       class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    + Nueva IP
                </button>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <!-- Mensajes de feedback -->
    @if (session()->has('message'))
        <div class="mb-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-800 font-medium">{{ session('message') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-green-400 hover:text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.style.display='none'" class="text-red-400 hover:text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Filtros de búsqueda -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Filtro por bloque A -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bloque A</label>
            <select wire:model.live="bloque_a" class="input-full">
                <option value="todos">Todos los bloques</option>
                @foreach ($bloques_a as $bloque)
                    <option value="{{ $bloque }}">{{ $bloque }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Filtro por bloque B -->
        @if ($bloque_a !== 'todos')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bloque B</label>
            <select wire:model.live="bloque_b" class="input-full">
                <option value="todos">Todos los bloques</option>
                @foreach ($bloques_b as $bloque)
                    <option value="{{ $bloque }}">{{ $bloque }}</option>
                @endforeach
            </select>
        </div>
        @endif
        
        <!-- Filtro por bloque C -->
        @if ($bloque_b !== 'todos')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bloque C</label>
            <select wire:model.live="bloque_c" class="input-full">
                <option value="todos">Todos los bloques</option>
                @foreach ($bloques_c as $bloque)
                    <option value="{{ $bloque }}">{{ $bloque }}</option>
                @endforeach
            </select>
        </div>
        @endif
        
        <!-- Buscador -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda</label>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Buscar por IP, nombre o descripción..." 
                   class="input-full">
        </div>
    </div>

    <!-- Tabla con diseño moderno -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        IP
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Descripción
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($ips as $ip)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $ip->ip }}
                        </div>
                        @if($ip->mac)
                        <div class="text-xs text-gray-500">
                            MAC: {{ $ip->mac }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $ip->nombre }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $ip->descripcion ?: 'Sin descripción' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button wire:click="checkIpStatus('{{ $ip->ip }}')" 
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors">
                            Verificar
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            @can('AdminIP/IPS/Editar')
                            <button wire:click="editIp({{ $ip->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </button>
                            @endcan
                            @can('AdminIP/IPS/Eliminar')
                            <button wire:click="deleteIp({{ $ip->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron IPs</h3>
                            @if(strlen($search) > 0 || $bloque_a !== 'todos')
                                <p class="text-gray-500">Intente modificar los filtros de búsqueda.</p>
                            @else
                                <p class="text-gray-500">No hay IPs registradas en el sistema.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Paginación -->
        @if($ips->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $ips->links() }}
        </div>
        @endif
    </div>

    <!-- Modal de edición -->
    @if($showEditModal && $selectedIp)
        <x-dialog-modal wire:model="showEditModal">
            <x-slot name="title">
                Editar IP: {{ $selectedIp->ip }}
            </x-slot>
            <x-slot name="content">
                @livewire('adminip.ips.index.editar', ['ip' => $selectedIp], key('edit-'.$selectedIp->id))
            </x-slot>
            <x-slot name="footer">
            </x-slot>
        </x-dialog-modal>
    @endif

    <!-- Modal de eliminación -->
    @if($showDeleteModal && $selectedIp)
        <x-dialog-modal wire:model="showDeleteModal">
            <x-slot name="title">
                Confirmar eliminación
            </x-slot>
            <x-slot name="content">
                <p class="text-gray-600">¿Está seguro que desea eliminar la IP <strong>{{ $selectedIp->ip }}</strong>?</p>
                <p class="text-sm text-gray-500 mt-2">Esta acción no se puede deshacer.</p>
            </x-slot>
            <x-slot name="footer">
                <div class="flex justify-end space-x-2">
                    <button wire:click="closeModals" 
                            class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm rounded transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="confirmDelete" 
                            class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm rounded transition-colors">
                        Eliminar
                    </button>
                </div>
            </x-slot>
        </x-dialog-modal>
    @endif

    <!-- Modal de verificación de IP -->
    @if($showCheckModal)
        <x-dialog-modal wire:model="showCheckModal">
            <x-slot name="title">
                Verificando IP: {{ $checkingIp }}
            </x-slot>
            <x-slot name="content">
                @if($isChecking)
                    <div class="flex flex-col items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-4"></div>
                        <p class="text-gray-600">Verificando conectividad...</p>
                        <p class="text-sm text-gray-500 mt-2">Esto puede tomar unos segundos</p>
                    </div>
                @elseif($checkResult)
                    <div class="py-4">
                        @if($checkResult['status'] === 'success')
                            <div class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg">
                                <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-green-800 font-medium">{{ $checkResult['message'] }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-lg">
                                <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-red-800 font-medium">{{ $checkResult['message'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </x-slot>
            <x-slot name="footer">
                <div class="flex justify-end">
                    <button wire:click="closeModals" 
                            class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm rounded transition-colors">
                        Cerrar
                    </button>
                </div>
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
