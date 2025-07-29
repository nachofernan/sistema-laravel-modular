<x-app-layout>
    <x-page-header title="{{ $user->realname }}">
        <x-slot:actions>
            @can('Usuarios/Usuarios/Editar')
                <a href="{{ route('usuarios.users.edit', $user) }}" 
                   class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-md transition-colors">
                    Editar Usuario
                </a>
            @endcan
            <a href="{{ route('usuarios.users.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver
            </a>
        </x-slot:actions>
    </x-page-header>
    <div class="w-full max-w-7xl mx-auto">
        <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">

            <!-- Contenido principal -->
            <div class="block w-full overflow-x-auto py-6 px-5">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Columna izquierda: Datos del usuario -->
                    <div class="space-y-6">
                        <div>
                            <div class="subtitulo-show">
                                Información Personal y Empresarial
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                <div class="grid-datos-show">
                                    <div class="atributo-show">
                                        Nombre
                                    </div>
                                    <div class="valor-show">
                                        {{ $user->realname }}
                                    </div>
                                    <div class="atributo-show">
                                        Username
                                    </div>
                                    <div class="valor-show">
                                        {{ $user->name }}
                                    </div>
                                    <div class="atributo-show">
                                        Legajo
                                    </div>
                                    <div class="valor-show">
                                        {{ $user->legajo }}
                                    </div>
                                    <div class="atributo-show">
                                        Fecha de Registro
                                    </div>
                                    <div class="valor-show">
                                        {{ Carbon\Carbon::create($user->created_at)->format('d-m-Y H:i') }}
                                    </div>

                                    <div class="atributo-show">
                                        Último Acceso
                                    </div>
                                    <div class="valor-show">
                                        @if($user->LastAccess)
                                            {{ \Carbon\Carbon::create($user->LastAccess->created_at)->format('d-m-Y H:i') }}
                                        @elseif($user->last_login)
                                            {{ \Carbon\Carbon::create($user->last_login)->format('d-m-Y H:i') }}
                                        @else
                                            <span class="text-gray-500 italic">Nunca</span>
                                        @endif
                                    </div>

                                    <div class="atributo-show">
                                        Estado
                                    </div>
                                    <div class="valor-show">
                                        @if ($user->visible)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Visible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                No Visible
                                            </span>
                                        @endif
                                    </div>

                                    <div class="atributo-show">
                                        Correo Electrónico
                                    </div>
                                    <div class="valor-show">
                                        <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $user->email }}
                                        </a>
                                    </div>

                                    @if($user->interno)
                                    <div class="atributo-show">
                                        Interno
                                    </div>
                                    <div class="valor-show">
                                        {{ $user->interno }}
                                    </div>
                                    @endif

                                    @if($user->sede)
                                    <div class="atributo-show">
                                        Sede
                                    </div>
                                    <div class="valor-show">
                                        {{ $user->sede->nombre }}
                                    </div>
                                    @endif

                                    @if($user->area)
                                    <div class="atributo-show">
                                        Área
                                    </div>
                                    <div class="valor-show">
                                        {{ $user->area->nombre }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Historial de actividad -->
                        @if($user->logs && $user->logs->count() > 0)
                        <div>
                            <div class="subtitulo-show">
                                Últimos 10 Movimientos
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden mt-3">
                                <div class="max-h-64 overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Evento</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($user->logs->reverse()->take(10) as $log)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 py-2 text-xs text-gray-600 whitespace-nowrap">
                                                    {{ Carbon\Carbon::create($log->created_at)->format('d-m-Y H:i') }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-gray-900">
                                                    {{ $log->evento }}
                                                </td>
                                                <td class="px-3 py-2 text-xs text-gray-500 whitespace-nowrap">
                                                    {{ $log->ip_address }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @else
                        <div>
                            <div class="subtitulo-show">
                                Últimos 10 Movimientos
                            </div>
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Sin movimientos registrados</p>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <div class="subtitulo-show">
                                Otras acciones
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Livewire componente para reset password -->
                                    @livewire('usuarios.users.index.reset-password-modal', ['user' => $user], key($user->id.'-reset-'.microtime(true)))
                                    
                                    @can('Usuarios/Usuarios/Eliminar')
                                        <!-- Livewire componente para eliminar -->
                                        @livewire('usuarios.users.index.eliminar-modal', ['user' => $user], key($user->id.'-delete-'.microtime(true)))
                                    @endcan
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Columna derecha: Roles y permisos -->
                    <div class="space-y-6">
                        <div>
                            <div class="subtitulo-show">
                                Roles y Permisos
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                @if($user->getRoleNames()->count() > 0)
                                    <div class="space-y-2">
                                        @foreach ($user->getRoleNames()->sort() as $role)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-gray-900">{{ $role }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <svg class="h-8 w-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">Sin roles asignados</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Estadísticas adicionales -->
                        <div>
                            <div class="subtitulo-show">
                                Estadísticas
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 mt-3">
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-900">
                                            {{ $user->logs ? $user->logs->count() : 0 }}
                                        </div>
                                        <div class="text-sm text-gray-500">Total de accesos registrados</div>
                                    </div>
                                    
                                    @if($user->created_at)
                                    <div class="text-center border-t pt-4">
                                        <div class="text-lg font-semibold text-gray-900">
                                            {{ Carbon\Carbon::create($user->created_at)->diffForHumans() }}
                                        </div>
                                        <div class="text-sm text-gray-500">Tiempo en el sistema</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>