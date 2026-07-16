<x-app-layout>
    <x-page-header title="Cargos">
        <x-slot:actions>
            <a href="{{ route('usuarios.users.index') }}"
               class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors">
                Volver a Usuarios
            </a>
        </x-slot:actions>
    </x-page-header>

    @php $editing = $editing ?? null; @endphp

    <div class="w-full max-w-3xl mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 text-sm rounded-md p-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario de alta / edición -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">
                {{ $editing ? 'Editar cargo' : 'Nuevo cargo' }}
            </h3>

            <form action="{{ $editing ? route('usuarios.cargos.update', $editing) : route('usuarios.cargos.store') }}"
                  method="POST"
                  class="flex flex-wrap items-end gap-4">
                @csrf
                @if ($editing)
                    @method('PUT')
                @endif

                <div class="flex-1 min-w-[200px]">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                    <input type="text"
                           name="nombre"
                           id="nombre"
                           value="{{ old('nombre', $editing->nombre ?? '') }}"
                           placeholder="Gerente, Asistente, Analista…"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           required>
                    @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-28">
                    <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                    <input type="number"
                           name="orden"
                           id="orden"
                           min="0"
                           value="{{ old('orden', $editing->orden ?? 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('orden')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 transition-colors">
                        {{ $editing ? 'Actualizar' : 'Agregar' }}
                    </button>
                    @if ($editing)
                        <a href="{{ route('usuarios.cargos.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Listado -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Personas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($cargos as $cargo)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $cargo->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $cargo->orden }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $cargo->users_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('usuarios.cargos.edit', $cargo) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">
                                        Editar
                                    </a>
                                    <form action="{{ route('usuarios.cargos.destroy', $cargo) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este cargo? Las personas que lo tienen quedarán sin cargo.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded transition-colors">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">
                                Todavía no hay cargos cargados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
