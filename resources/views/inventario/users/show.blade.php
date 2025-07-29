<x-app-layout>
    <x-page-header title="{{ $user->realname }}">
        <x-slot:actions>
            <a href="{{ route('inventario.users.index') }}" 
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver al Listado
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="w-full mb-12 xl:mb-0 mx-auto">
        <!-- Información del usuario -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->realname }}</h2>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>Usuario: {{ $user->name }}</span>
                            <span>•</span>
                            <span>Legajo: {{ $user->legajo }}</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">{{ $user->elementos()->count() }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $user->elementos()->count() == 1 ? 'elemento asignado' : 'elementos asignados' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">{{ $user->elementos()->count() }}</div>
                        <div class="text-sm text-gray-500">Total Elementos</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">
                            {{ $user->elementos()->where('estado_id', 1)->count() }}
                        </div>
                        <div class="text-sm text-gray-500">Activos</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">
                            {{ $user->elementos()->where('estado_id', 2)->count() }}
                        </div>
                        <div class="text-sm text-gray-500">En Mantenimiento</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-gray-900">
                            {{ $user->elementos()->where('estado_id', 3)->count() }}
                        </div>
                        <div class="text-sm text-gray-500">Inactivos</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de elementos -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Elementos Asignados</h3>
            </div>
            
            <div class="p-6">
                @livewire('inventario.users.show.table-search', ['user' => $user], key($user->id . microtime(true)))
            </div>
        </div>
    </div>
</x-app-layout>