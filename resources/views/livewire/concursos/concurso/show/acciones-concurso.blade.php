<div>
    <button class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors" wire:click="$set('open', true)"> 
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mr-1">
            <path d="M8 4a.75.75 0 0 1 .75.75v2.5h2.5a.75.75 0 0 1 0 1.5h-2.5v2.5a.75.75 0 0 1-1.5 0v-2.5h-2.5a.75.75 0 0 1 0-1.5h2.5v-2.5A.75.75 0 0 1 8 4Z" />
        </svg>
        Acciones
    </button>
    <x-dialog-modal wire:model="open"> 
        <div class="max-w-10xl">
        <x-slot name="title"> 
            <div class="border-b py-2"> 
                Concurso: {{ $concurso->nombre }} #{{ $concurso->numero }}
            </div>
        </x-slot> 
        <x-slot name="content">
            @if ($concurso->estado->id == 1 && $concurso->fecha_cierre > now())
                <button 
                    wire:click="actualizarEstado(2)"
                    onclick="handleButtonClick22(this)"
                    class="bg-green-500 text-white font-bold text-lg w-full py-3 rounded-lg shadow hover:bg-green-600">
                    Publicar
                </button>
                <p class="text-sm italic mt-2">
                    Al publicar el concurso se enviarán las invitaciones a todos los proveedores seleccionados hasta el momento. 
                    En caso de agregar un nuevo proveedor, se deberá enviar la invitación de manera individual.
                    <br>
                    El concurso quedará público hasta tanto no finalice o se proceda a la baja.
                    <br>
                    Otros usuarios que recibirán el correo de aviso son: Vos, yo, cachi, pachi y aquellos de sagitario.
                </p>
                <script>
                    function handleButtonClick22(button) {
                        // Deshabilitar el botón
                        button.disabled = true;
                        // Cambiar texto del botón
                        button.innerText = "Enviando invitaciones...";
                        // Cambiar estilo (opcional)
                        button.classList.add("cursor-not-allowed", "opacity-50");
                    }
                </script>
            @elseif($concurso->estado->id == 2)
                @if ($concurso->fecha_cierre < now())
                    <button wire:click="actualizarEstado(3)" class="bg-green-500 text-white font-bold text-lg w-full py-3 rounded-lg shadow hover:bg-green-600">
                        Abrir Ofertas
                    </button>
                    <p class="text-sm italic mt-2">
                        Al hacer clic en el botón anterior se dará por terminado el proceso licitatorio y se habilitarán las opciones de ver las ofertas presentadas.
                        <br>
                        Los siguientes usuarios tendrán aviso de la apertura del concurso.
                    </p>
                @else
                    El concurso todavía no puede finalizar porque no ha pasado la fecha de cierre
                    @if ($concurso->fecha_cierre < now()->addDays(7))
                        <button wire:click="mailsRecordatorios()" class="bg-blue-500 text-white font-bold text-lg w-full py-3 rounded-lg shadow hover:bg-blue-600">
                            Mandar notificación de que está por cerrar
                        </button>
                    @endif
                @endif
            @elseif($concurso->estado->id == 3)
                <button wire:click="actualizarEstado(4)" class="bg-green-500 text-white font-bold text-lg w-full py-3 rounded-lg shadow hover:bg-green-600">
                    Marcar como Terminado
                </button>
                <p class="text-sm italic mt-2">
                    Finaliza completamente el proceso licitatorio y se da por terminado el concurso.
                </p>
            @endif
            @can('Concursos/Concursos/Anular')
            <div class="bg-gray-100 p-4 mt-4 rounded-lg">
                <p class="text-sm italic pb-4">
                    Al dar de baja el concurso se dará como terminado y se eliminarán las ofertas presentadas.
                    <br>
                    Los siguientes usuarios tendrán aviso de la anulación del concurso.
                </p>
                <div class="flex justify-between">
                    <span class="mt-3">Ingrese clave para dar de baja</span>
                    <input type="password" wire:model.live="test" class="rounded mx-2">
                    <button wire:click="delete()" class="boton-celeste text-white bg-red-600 hover:bg-red-800
                        @if ($test != 'clave') cursor-not-allowed opacity-50 @endif
                    ">
                        Anular Concurso
                    </button>
                </div>
            </div>
            @endcan
        </x-slot> 
        <x-slot name="footer">
            <button wire:click="$set('open', false)" class="boton-celeste text-white bg-gray-600 hover:bg-gray-800">
                Cerrar
            </button>
        </x-slot>  
        </div>
    </x-dialog-modal> 
</div>
