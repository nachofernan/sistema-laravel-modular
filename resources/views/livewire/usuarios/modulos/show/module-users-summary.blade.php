<div>
    <!-- Botón para mostrar resumen de usuarios -->
    <button wire:click="showModuleUsers" 
            class="inline-flex items-center px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-sm rounded-md transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
        </svg>
        Ver Usuarios del Módulo
        @if($totalUsers > 0)
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20">
                {{ $totalUsers }}
            </span>
        @endif
    </button>
    
    <!-- Modal para mostrar resumen de usuarios -->
    <x-dialog-modal wire:model="open" maxWidth="4xl"> 
        <x-slot name="title">
            <h3 class="text-lg font-medium text-gray-900">Usuarios del Módulo</h3>
            <p class="text-sm text-gray-500">{{ $modulo->nombre }}</p>
        </x-slot> 
        
        <x-slot name="content"> 
            @if($users && $users->count() > 0)
                <div class="space-y-6">
                    <!-- Resumen por roles -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-800 mb-3">Distribución por Roles</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($roleCounts as $roleName => $count)
                                @if($count > 0)
                                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-blue-200">
                                        <span class="text-sm text-gray-700 truncate">{{ $roleName }}</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $count }} usuario(s)
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Lista de usuarios únicos -->
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h4 class="text-lg font-medium text-gray-900">
                                Usuarios Únicos ({{ $users->count() }})
                            </h4>
                            <p class="text-sm text-gray-500">Lista de usuarios que tienen al menos un rol en este módulo</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Usuario
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Roles en el Módulo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Área
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    @if($user->profile_photo_url)
                                                        <img class="h-8 w-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                                    @else
                                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->realname }}
                                                    </div>
                                                    @if($user->name)
                                                        <div class="text-sm text-gray-500">
                                                            {{ $user->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                            @if($user->legajo)
                                                <div class="text-sm text-gray-500">Legajo: {{ $user->legajo }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($roleUsers as $roleName => $roleUserList)
                                                    @if($roleUserList->contains('id', $user->id))
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ str_replace($modulo->nombre . '/', '', $roleName) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->area)
                                                <span class="text-sm text-gray-900">{{ $user->area->nombre }}</span>
                                            @else
                                                <span class="text-sm text-gray-500">Sin área</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->visible)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay usuarios asignados</h3>
                    <p class="text-gray-500">Este módulo no tiene usuarios con roles asignados actualmente.</p>
                </div>
            @endif
        </x-slot>
        
        <x-slot name="footer">
            <div class="flex justify-end">
                <button wire:click="$set('open', false)" 
                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-md transition-colors">
                    Cerrar
                </button>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
