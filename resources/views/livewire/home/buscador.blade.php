<div>
    {{-- Stop trying to control. --}}
    
    <!-- Filtros de búsqueda -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar personal</label>
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Buscar usuario por nombre o interno" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sede</label>
                <select wire:model.live="sede_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todas las sedes</option>
                    @foreach ($sedes as $sede)
                        <option value="{{$sede->id}}" {{$sede->id == $sede_id ? 'selected' : ''}}>{{$sede->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla de resultados -->
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Interno
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre y Apellido
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Correo Electrónico
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 text-xl font-bold text-gray-800">
                                    {{ $usuario->interno }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-lg font-medium text-gray-800">
                                        {{ $usuario->realname }}
                                    </div>
                                    @if($usuario->area)
                                    <div class="text-xs text-gray-500">
                                        {{ $usuario->area->nombre }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-blue-600 hover:text-blue-800">
                                    <a href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron usuarios</h3>
                                    <p class="text-gray-500">Intente ajustar los filtros de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($usuarios->hasPages())
            <div class="mt-6">
                {{ $usuarios->links() }}
            </div>
            @endif
        </div>
    </div>
</div>