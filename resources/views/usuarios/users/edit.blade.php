<x-app-layout>
    <x-page-header title="Editar Usuario">
        <x-slot:subtitle>
            #{{ $user->id }} - {{ $user->realname }}
        </x-slot:subtitle>
        <x-slot:actions>
            <a href="{{ route('usuarios.users.show', $user) }}" 
            class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver
            </a>
        </x-slot:actions>
    </x-page-header>
    
    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('usuarios.users.update', $user) }}" method="POST" x-data="{ loading: false }" @submit="loading = true">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    
                    <!-- Header del formulario -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Editar Datos del Usuario</h3>
                                <p class="mt-1 text-sm text-gray-500">Modifique la información del usuario {{ $user->realname }}</p>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                        :disabled="loading"
                                        :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                    <span x-show="!loading">
                                        Actualizar Usuario
                                    </span>
                                    <span x-show="loading" class="flex items-center">
                                        Actualizando...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido del formulario -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <!-- Columna izquierda - Datos del usuario -->
                            <div class="space-y-6">
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Información Personal</h4>
                                    <p class="mt-1 text-sm text-gray-500">Datos básicos del usuario en el sistema</p>
                                </div>
                                
                                <!-- Nombre Real -->
                                <div>
                                    <label for="realname" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre Completo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="realname"
                                           name="realname" 
                                           value="{{ old('realname', $user->realname) }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('realname') border-red-300 @enderror" 
                                           placeholder="Nombre y Apellido"
                                           required 
                                           autocomplete="name">
                                    @error('realname')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre de Usuario -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre de Usuario
                                    </label>
                                    <input type="text" 
                                           id="name"
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 @enderror" 
                                           placeholder="usuario.ejemplo"
                                           autocomplete="username">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Correo Electrónico -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo Electrónico <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email"
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror" 
                                           placeholder="usuario@empresa.com"
                                           required 
                                           autocomplete="email">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Legajo -->
                                <div>
                                    <label for="legajo" class="block text-sm font-medium text-gray-700 mb-2">
                                        Legajo <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="legajo"
                                           name="legajo" 
                                           value="{{ old('legajo', $user->legajo) }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('legajo') border-red-300 @enderror" 
                                           placeholder="Número de legajo"
                                           required 
                                           autocomplete="off">
                                    @error('legajo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Interno -->
                                <div>
                                    <label for="interno" class="block text-sm font-medium text-gray-700 mb-2">
                                        Interno
                                    </label>
                                    <input type="text" 
                                           id="interno"
                                           name="interno" 
                                           value="{{ old('interno', $user->interno) }}" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('interno') border-red-300 @enderror" 
                                           placeholder="Número de interno"
                                           autocomplete="off">
                                    @error('interno')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sede -->
                                <div>
                                    <label for="sede_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sede
                                    </label>
                                    <select id="sede_id" 
                                            name="sede_id" 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sede_id') border-red-300 @enderror">
                                        <option value="">Seleccionar sede...</option>
                                        @foreach ($sedes as $sede)
                                            <option value="{{ $sede->id }}" 
                                                {{ old('sede_id', $user->sede_id) == $sede->id ? 'selected' : '' }}>
                                                {{ $sede->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sede_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Área -->
                                <div>
                                    <label for="area_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Área
                                    </label>
                                    <select id="area_id" 
                                            name="area_id" 
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('area_id') border-red-300 @enderror">
                                        <option value="">Seleccionar área...</option>
                                        @livewire('usuarios.areas.foreach-select', ['areas' => $areas, 'area_id' => old('area_id', $user->area_id ?? 0), 'disabled' => false, 'nivel' => ''])
                                    </select>
                                    @error('area_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Visibilidad -->
                                <div>
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="visible"
                                               name="visible" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               {{ old('visible', $user->visible) ? 'checked' : '' }}>
                                        <label for="visible" class="ml-2 block text-sm text-gray-900">
                                            Visible en el buscador
                                        </label>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Si está marcado, el usuario aparecerá en las búsquedas del sistema</p>
                                </div>

                                <!-- Información de registro -->
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Información del Sistema</h5>
                                    <div class="space-y-1 text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Registrado:</span> 
                                            {{ Carbon\Carbon::create($user->created_at)->format('d-m-Y H:i') }}
                                        </div>
                                        @if($user->updated_at && $user->updated_at != $user->created_at)
                                        <div>
                                            <span class="font-medium">Última modificación:</span> 
                                            {{ Carbon\Carbon::create($user->updated_at)->format('d-m-Y H:i') }}
                                        </div>
                                        @endif
                                        <div>
                                            <span class="font-medium">Último acceso:</span> 
                                            @if($user->LastAccess)
                                                {{ \Carbon\Carbon::create($user->LastAccess->created_at)->format('d-m-Y H:i') }}
                                            @elseif($user->last_login)
                                                {{ \Carbon\Carbon::create($user->last_login)->format('d-m-Y H:i') }}
                                            @else
                                                <span class="text-gray-500">Nunca</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna derecha - Roles y permisos -->
                            <div class="space-y-6">
                                @can('Usuarios/Usuarios/Roles')
                                <div class="border-b border-gray-200 pb-4">
                                    <h4 class="text-sm font-medium text-gray-900 uppercase tracking-wider">Roles y Permisos</h4>
                                    <p class="mt-1 text-sm text-gray-500">Modifique los roles asignados al usuario</p>
                                </div>

                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @foreach ($modulos as $modulo)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <h5 class="text-sm font-medium text-gray-900">{{ $modulo->nombre }}</h5>
                                                @if ($modulo->estado == 'mantenimiento')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Mantenimiento
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="space-y-2">
                                                @foreach ($modulo->roles() as $role)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           id="role_{{ $role->name }}"
                                                           name="roles[{{ $role->name }}]"
                                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                           {{ old('roles.'.$role->name, $user->hasRole($role->name)) ? 'checked' : '' }}>
                                                    <label for="role_{{ $role->name }}" class="ml-2 block text-sm text-gray-900">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">
                                                Sin permisos para modificar roles
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>No tiene permisos para modificar los roles asignados a este usuario.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endcan

                                <!-- Nota informativa -->
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">
                                                Información importante
                                            </h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Los campos marcados con <span class="text-red-500 font-medium">*</span> son obligatorios</li>
                                                    <li>Los cambios se aplicarán inmediatamente</li>
                                                    <li>Cambiar roles puede afectar el acceso del usuario al sistema</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>