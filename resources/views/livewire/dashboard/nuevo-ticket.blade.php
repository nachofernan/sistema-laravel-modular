<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-900">Nuevo Ticket</h3>
    </div>

    <!-- Formulario -->
    <div class="p-6">
        <form wire:submit="crearTicket" class="space-y-4">
            
            <!-- Categoría -->
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría *
                </label>
                <div class="relative">
                    <select wire:model.live="categoria_id" 
                            id="categoria"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 appearance-none bg-white pr-10"
                            {{ $enviando ? 'disabled' : '' }}>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
                @error('categoria_id') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Mensaje/Descripción -->
            <div>
                <label for="notas" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción del problema *
                </label>
                <textarea wire:model="notas" 
                          id="notas"
                          rows="4" 
                          placeholder="Describe detalladamente tu consulta o problema. Incluye pasos para reproducir el error, capturas de pantalla si es necesario, etc."
                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 resize-vertical"
                          maxlength="1000"
                          {{ $enviando ? 'disabled' : '' }}></textarea>
                <div class="flex justify-between items-center mt-1">
                    @error('notas') 
                        <p class="text-sm text-red-600">{{ $message }}</p> 
                    @else
                        <p class="text-sm text-gray-500">Mínimo 10 caracteres</p>
                    @enderror
                    <span class="text-xs text-gray-400">
                        {{ strlen($notas ?? '') }}/1000
                    </span>
                </div>
            </div>

            <!-- Archivo adjunto -->
            <div>
                <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">
                    Archivo adjunto
                    @if($categoria_id == 9)
                        <span class="text-red-500">*</span>
                    @else
                        <span class="text-gray-500 font-normal">(opcional)</span>
                    @endif
                </label>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input wire:model="documento" 
                               type="file" 
                               id="documento"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt,.zip"
                               class="w-full text-sm border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"
                               {{ $enviando ? 'disabled' : '' }}>
                    </div>
                    @if($documento)
                        <div class="flex items-center text-sm text-green-600">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Archivo seleccionado
                        </div>
                    @endif
                </div>
                @error('documento') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @enderror
                <p class="mt-1 text-xs text-gray-500">
                    Formatos permitidos: PDF, DOC, DOCX, JPG, PNG (máx. 10MB)
                </p>
            </div>

            <!-- Botón de envío -->
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        wire:loading.attr="disabled"
                        {{ $enviando ? 'disabled' : '' }}>
                    
                    <span wire:loading.remove wire:target="crearTicket">
                        Enviar Ticket
                    </span>
                    
                    <span wire:loading wire:target="crearTicket" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>