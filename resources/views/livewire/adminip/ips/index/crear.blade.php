<div>
    <!-- Modal de creación -->
    @if($showModal)
        <x-dialog-modal wire:model="showModal">
            <x-slot name="title">
                Crear Nueva IP
            </x-slot>
            <x-slot name="content">
                <form wire:submit="guardar()">
                    <div class="space-y-4">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" wire:model="nombre" class="input-full" placeholder="Nombre del equipo" required>
                            @error('nombre') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- IP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección IP</label>
                            <input type="text" wire:model="ip" class="input-full" placeholder="192.168.1.1" required>
                            @error('ip') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- MAC -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección MAC</label>
                            <input type="text" wire:model="mac" class="input-full" placeholder="00:11:22:33:44:55">
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea wire:model="descripcion" class="input-full" rows="3" placeholder="Descripción del equipo"></textarea>
                        </div>

                        <!-- Usuario SSH -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario SSH</label>
                            <input type="text" wire:model="user" class="input-full" placeholder="usuario" autocomplete="off">
                        </div>

                        <!-- Contraseña SSH -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña SSH</label>
                            <input type="text" wire:model="password" class="input-full" placeholder="contraseña" autocomplete="new-password">
                        </div>

                        <!-- Usuario Asignado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario Asignado</label>
                            <select wire:model="user_id" class="input-full">
                                <option value="">Sin usuario asignado</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->realname }} ({{ $user->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-2 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" 
                                wire:click="closeModal" 
                                class="px-3 py-1.5 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm rounded transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded transition-colors">
                            Crear IP
                        </button>
                    </div>
                </form>
            </x-slot>
            <x-slot name="footer">
            </x-slot>
        </x-dialog-modal>
    @endif
</div>
