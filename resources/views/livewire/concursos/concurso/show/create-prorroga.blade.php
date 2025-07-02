<div>
    {{-- Success is as dangerous as failure. --}}
    <button class="link-azul float-right text-sm mr-4 font-normal flex items-center" wire:click="$set('open', true)"> 
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4 mr-1">
            <path d="M5.75 7.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM5 10.25a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0ZM10.25 7.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM7.25 8.25a.75.75 0 1 1 1.5 0 .75.75 0 0 1-1.5 0ZM8 9.5A.75.75 0 1 0 8 11a.75.75 0 0 0 0-1.5Z" />
            <path fill-rule="evenodd" d="M4.75 1a.75.75 0 0 0-.75.75V3a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2V1.75a.75.75 0 0 0-1.5 0V3h-5V1.75A.75.75 0 0 0 4.75 1ZM3.5 7a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v4.5a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1V7Z" clip-rule="evenodd" />
        </svg>          
        Prórroga
    </button>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2"> 
                Nueva Prórroga
            </div>                 
                
                
        </x-slot> 
        <x-slot name="content">
            <form action="{{ route('concursos.prorrogas.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid-datos-show">
                    <input type="hidden" name="concurso_id" value="{{ $concurso->id }}">
                    <div class="atributo-edit">
                        Nueva Fecha de Cierre
                    </div>
                    <div class="valor-edit">
                        <input type="datetime-local" name="fecha_cierre" class="input-full" required>
                    </div>
                    <div class="atributo-edit">
                    </div>
                    <div class="valor-edit text-right">
                        <button 
                            class="boton-celeste text-sm"
                            @if ($concurso->estado->id > 1)
                                onclick="return prorrogabutton(this)"
                            @endif
                            >
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
            <script>
                function prorrogabutton(button) {
                    // Deshabilitar el botón
                    button.disabled = true;
                    // Cambiar texto del botón
                    button.innerText = "Enviando avisos... Espere por favor";
                    // Cambiar estilo (opcional)
                    button.classList.add("cursor-not-allowed", "opacity-50");
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