<div>
    <button wire:click="$set('open', true)" 
            class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded-md transition-colors">
        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        Editar Ticket
    </button>

    <x-dialog-modal wire:model="open" maxWidth="lg">
        <x-slot name="title">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="text-base font-medium text-gray-900">Editar Ticket #{{ $ticket->codigo }}</span>
            </div>
        </x-slot>

        <x-slot name="content">
            <!-- Información del ticket -->
            <div class="bg-gray-50 rounded-md p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">#{{ $ticket->codigo }}</div>
                        <div class="text-xs text-gray-500">{{ $ticket->user->realname }} • {{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('tickets.tickets.update', $ticket) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Categoría -->
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Categoría *
                        </label>
                        <select name="categoria_id" 
                                id="categoria_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" 
                                        {{ $ticket->categoria_id == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="estado_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado *
                        </label>
                        <select name="estado_id" 
                                id="estado_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}" 
                                        {{ $ticket->estado_id == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Encargado -->
                <div>
                    <label for="user_encargado_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Encargado
                    </label>
                    <select name="user_encargado_id" 
                            id="user_encargado_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sin asignar</option>
                        @foreach ($users as $user)
                            @php
                                if($user->legajo == '00000') { continue; }
                            @endphp
                            <option value="{{ $user->id }}" 
                                    {{ $ticket->user_encargado_id == $user->id ? 'selected' : '' }}>
                                {{ $user->realname }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Información adicional -->
                <div class="bg-blue-50 rounded-md p-3">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-4 w-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-blue-700">
                                <strong>Nota:</strong> Los cambios se aplicarán inmediatamente y se notificarán al solicitante del ticket.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-between items-center pt-4">
                    <button wire:click="$set('open', false)" 
                            type="button"
                            class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-3 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md shadow-sm hover:bg-yellow-700 transition-colors">
                        <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Actualizar Ticket
                    </button>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
        </x-slot>
    </x-dialog-modal>
</div>