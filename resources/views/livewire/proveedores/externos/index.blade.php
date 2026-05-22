<div class="p-4" x-data>

    @if (session('success'))
        <div class="mb-3 px-4 py-2 bg-green-100 border border-green-300 text-green-800 text-sm rounded-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="grid grid-cols-12 gap-2 mb-4">
        <div class="col-span-7">
            <input wire:model.live="search"
                   type="text"
                   placeholder="Buscar por CUIT o correo electrónico"
                   class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="col-span-5 flex items-center gap-2">
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                <input wire:model.live="soloSinVincular" type="checkbox"
                       class="rounded border-gray-300 text-blue-500 focus:ring-blue-500">
                Solo sin proveedor vinculado
            </label>
        </div>
    </div>

    {{-- Tabla --}}
    @if ($users->count())
        <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">CUIT / Usuario</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo Electrónico</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Registrado</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Último acceso</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Cambiar Pass.</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor Interno</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-2 text-xs text-gray-900 font-mono">{{ $user->username }}</td>
                            <td class="px-3 py-2 text-xs text-gray-700">
                                <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800 break-all">
                                    {{ $user->email }}
                                </a>
                            </td>
                            <td class="px-3 py-2 text-center text-xs text-gray-500">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td class="px-3 py-2 text-center text-xs text-gray-500">
                                {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td class="px-3 py-2 text-center">
                                @if ($user->must_change_password)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pendiente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        OK
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-xs">
                                @if ($user->proveedorInterno)
                                    <a href="{{ route('proveedores.proveedors.show', $user->proveedorInterno->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $user->proveedorInterno->razonsocial }}
                                    </a>
                                    <span class="text-gray-400 ml-1">(#{{ $user->proveedorInterno->id }})</span>
                                @else
                                    <span class="text-gray-400 italic">Sin vinculación</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center">
                                @can('Proveedores/Externos/Editar')
                                    <div class="flex flex-col gap-1 items-center">
                                        <button wire:click="abrirModal({{ $user->id }})"
                                                class="w-full px-2.5 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded-md transition-colors">
                                            Blanquear Pass.
                                        </button>
                                        @if ($user->proveedorInterno)
                                            <button wire:click="abrirEmailModal({{ $user->id }})"
                                                    @class([
                                                        'w-full px-2.5 py-1 text-xs rounded-md transition-colors',
                                                        'bg-sky-500 hover:bg-sky-600 text-white' => $user->email !== $user->proveedorInterno->correo,
                                                        'bg-gray-100 hover:bg-gray-200 text-gray-500' => $user->email === $user->proveedorInterno->correo,
                                                    ])>
                                                Sync correo
                                            </button>
                                        @endif
                                    </div>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-center">
            {{ $users->links() }}
        </div>
    @else
        <div class="text-center py-10 text-gray-500">
            <div class="text-lg font-medium">No se encontraron usuarios externos</div>
        </div>
    @endif

    {{-- Modal de reset de contraseña --}}
    @if ($modalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center"
             @keydown.escape.window="$wire.cerrarModal()">

            {{-- Fondo oscuro --}}
            <div class="absolute inset-0 bg-black/50" wire:click="cerrarModal"></div>

            {{-- Contenido del modal --}}
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 z-10">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Blanquear Contraseña</h3>
                </div>

                <div class="px-6 py-4 space-y-3">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Usuario (CUIT):</span>
                        <span class="font-mono ml-1 text-gray-900">{{ $selectedUsername }}</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Correo:</span>
                        <span class="ml-1 text-gray-900">{{ $selectedEmail }}</span>
                    </div>

                    @if (!$confirmacionOpen)
                        <div class="pt-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nueva contraseña provisoria
                            </label>
                            <input wire:model="nuevaPassword"
                                   type="text"
                                   placeholder="Ej: Temporal2025"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                            @error('nuevaPassword')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <p class="text-xs text-gray-500">
                            El usuario deberá cambiar la contraseña en su próximo inicio de sesión.
                        </p>
                    @else
                        <div class="bg-orange-50 border border-orange-200 rounded-md p-3">
                            <p class="text-sm text-orange-800 font-medium">¿Confirmás el blanqueo de contraseña?</p>
                            <p class="text-xs text-orange-700 mt-1">
                                Se establecerá la contraseña provisoria y se marcará como pendiente de cambio.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-3 border-t border-gray-200 flex justify-end gap-2">
                    <button wire:click="cerrarModal"
                            class="px-4 py-1.5 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>

                    @if (!$confirmacionOpen)
                        <button wire:click="confirmar"
                                class="px-4 py-1.5 text-sm bg-orange-500 hover:bg-orange-600 text-white rounded-md transition-colors">
                            Continuar
                        </button>
                    @else
                        <button wire:click="resetearPassword"
                                class="px-4 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                            Sí, blanquear contraseña
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de sincronización de correo --}}
    @if ($emailModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center"
             @keydown.escape.window="$wire.cerrarEmailModal()">

            <div class="absolute inset-0 bg-black/50" wire:click="cerrarEmailModal"></div>

            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 z-10">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Sincronizar Correo Electrónico</h3>
                </div>

                <div class="px-6 py-4 space-y-3">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Usuario (CUIT):</span>
                        <span class="font-mono ml-1 text-gray-900">{{ $emailUsername }}</span>
                    </div>

                    <div class="rounded-md border border-gray-200 divide-y divide-gray-100 text-sm overflow-hidden">
                        <div class="flex items-center px-3 py-2 bg-red-50 gap-3">
                            <span class="text-xs font-medium text-gray-500 w-16 shrink-0">Actual</span>
                            <span class="text-gray-800 break-all">{{ $emailActual ?: '(vacío)' }}</span>
                        </div>
                        <div class="flex items-center px-3 py-2 bg-green-50 gap-3">
                            <span class="text-xs font-medium text-gray-500 w-16 shrink-0">Nuevo</span>
                            <span class="text-green-800 font-medium break-all">{{ $emailNuevo ?: '(vacío)' }}</span>
                        </div>
                    </div>

                    @if (!$emailNuevo)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <p class="text-sm text-yellow-800">El proveedor interno no tiene correo registrado. No se puede sincronizar.</p>
                        </div>
                    @elseif ($emailActual === $emailNuevo)
                        <p class="text-xs text-gray-500">Los correos ya coinciden. Podés confirmar de todas formas para forzar la actualización.</p>
                    @else
                        <p class="text-xs text-gray-500">Se reemplazará el correo del usuario externo con el del proveedor interno.</p>
                    @endif
                </div>

                <div class="px-6 py-3 border-t border-gray-200 flex justify-end gap-2">
                    <button wire:click="cerrarEmailModal"
                            class="px-4 py-1.5 text-sm border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    @if ($emailNuevo)
                        <button wire:click="sincronizarEmail"
                                class="px-4 py-1.5 text-sm bg-sky-600 hover:bg-sky-700 text-white rounded-md transition-colors">
                            Confirmar sincronización
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
