<div>
    <button class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors" wire:click="$set('open', true)"> 
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
            <path d="M8 4a.75.75 0 0 1 .75.75v2.5h2.5a.75.75 0 0 1 0 1.5h-2.5v2.5a.75.75 0 0 1-1.5 0v-2.5h-2.5a.75.75 0 0 1 0-1.5h2.5v-2.5A.75.75 0 0 1 8 4Z" />
        </svg>
        Agregar
    </button>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2 grid grid-cols-2"> 
                <div class="font-bold col mt-1">
                    Nueva Documentación
                </div>
            </div>                 
        </x-slot> 
        <x-slot name="content">
            <form action="{{ route('concursos.documentos.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid-datos-show">
                    <input type="hidden" name="concurso_id" value="{{ $concurso->id }}">
                    <div class="atributo-edit">
                        Tipo de Documento
                    </div>
                    <div class="valor-edit">
                        <select name="documento_tipo_id" class="input-full">
                            @foreach ($documento_tipos as $documento_tipo)
                                <option value="{{$documento_tipo->id}}">{{ $documento_tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="atributo-edit">
                        Archivo
                    </div>
                    <div class="valor-edit">
                        <input type="file" name="file" class="input-full" placeholder="Seleccione el archivo" required>
                    </div>
                    <div class="atributo-edit">
                    </div>
                    <div class="valor-edit text-right">
                        <button 
                            class="boton-celeste text-sm"
                            @if ($concurso->estado->id > 1)
                                onclick="return documentobutton(this)"
                            @endif
                        >
                        Guardar</button>
                    </div>
                </div>
            </form>
            <script>
                function documentobutton(button) {
                    // Obtener el checkbox
                    const mailsCheckbox = document.getElementById("mailsCheckbox");
                    // Verificar si el checkbox está marcado
                    if (mailsCheckbox && mailsCheckbox.checked) {
                        // Deshabilitar el botón
                        button.disabled = true;
                        // Cambiar texto del botón
                        button.innerText = "Enviando avisos... Espere por favor";
                        // Cambiar estilo (opcional)
                        button.classList.add("cursor-not-allowed", "opacity-50");
                    }
                    // Enviar formulario
                    button.form.submit();
                }
            </script>
        </x-slot> 
        <x-slot name="footer">
        </x-slot> 
        </div>
    </x-dialog-modal> 
</div>
