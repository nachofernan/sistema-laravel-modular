<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    
    <!-- Header con título y acciones -->
    <x-page-header title="Listado de Usuarios">
        <x-slot:actions>
            @can('Usuarios/Usuarios/Crear')
                <a href="{{ route('usuarios.users.create') }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    + Nuevo Usuario
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <!-- Filtros de búsqueda -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Filtro por sede -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Sede</label>
            <select wire:model.live="sede_id" class="input-full">
                <option value="0">Todas las sedes</option>
                @foreach ($sedes as $sede)
                    <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Buscador -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda</label>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Buscar por nombre o username..." 
                   class="input-full">
        </div>
    </div>

    <!-- Tabla con diseño mejorado -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Legajo
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Username
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nombre Completo
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Visible
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Último Acceso
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $user->legajo }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $user->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $user->realname }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if ($user->visible)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Sí
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                No
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{ $user->LastAccess ? \Carbon\Carbon::create($user->LastAccess->created_at)->format('d-m-Y H:i') : 'Nunca' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('usuarios.users.show', $user) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded transition-colors">
                                Ver Detalles
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron usuarios</h3>
                            @if(strlen($search) > 0 || $sede_id > 0)
                                <p class="text-gray-500">Intente modificar los filtros de búsqueda.</p>
                            @else
                                <p class="text-gray-500">No hay usuarios registrados en el sistema.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Paginación -->
        @if($users->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>