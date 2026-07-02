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
                @if ($concurso->fecha_cierre->subDays(3) < now())
                    <span
                        class="border-l-4 border-l-red-700 text-red-700 text-md w-full p-3 block mt-2">
                        El concurso no cumple con el requisito mínimo de 3 días antes de la fecha de cierre
                    </span>
                @endif
                <p class="text-sm italic mt-4 border-t border-gray-200 pt-2">
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
                    <p class="text-sm italic mt-4 border-t border-gray-200 pt-2">
                        Al hacer clic en el botón anterior se dará por terminado el proceso licitatorio y se habilitarán las opciones de ver las ofertas presentadas.
                        <br>
                        Los siguientes usuarios tendrán aviso de la apertura del concurso.
                    </p>

                    @php
                        $invitaciones      = $concurso->invitaciones;
                        $conOferta         = $invitaciones->where('intencion', 3);
                        $sinRespuesta      = $invitaciones->where('intencion', 0);
                        $noParticipan      = $invitaciones->where('intencion', 2);
                        $sinOferta         = $invitaciones->whereNotIn('intencion', [3]);
                        $docsADesencriptar = $conOferta->sum(fn($i) => $i->documentos->where('encriptado', true)->count());
                        $docsAEliminar     = $sinOferta->sum(fn($i) => $i->documentos->count());
                    @endphp
                    <div class="mt-4 border border-gray-200 rounded-lg overflow-hidden text-sm">
                        <div class="bg-gray-50 px-4 py-2 font-medium text-gray-600 border-b border-gray-200">
                            Resumen al momento de apertura
                        </div>
                        <table class="w-full">
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-2 text-gray-600">Invitados totales</td>
                                    <td class="px-4 py-2 text-right font-medium">{{ $invitaciones->count() }}</td>
                                </tr>
                                <tr class="bg-green-50">
                                    <td class="px-4 py-2 text-green-700">Con oferta presentada (intencion 3)</td>
                                    <td class="px-4 py-2 text-right font-medium text-green-700">{{ $conOferta->count() }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-gray-500">Sin respuesta</td>
                                    <td class="px-4 py-2 text-right font-medium text-gray-500">{{ $sinRespuesta->count() }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-gray-500">No participan</td>
                                    <td class="px-4 py-2 text-right font-medium text-gray-500">{{ $noParticipan->count() }}</td>
                                </tr>
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2 text-blue-600">Documentos a desencriptar</td>
                                    <td class="px-4 py-2 text-right font-medium text-blue-600">{{ $docsADesencriptar }}</td>
                                </tr>
                                <tr class="bg-red-50">
                                    <td class="px-4 py-2 text-red-600">Documentos a eliminar (ofertas no presentadas)</td>
                                    <td class="px-4 py-2 text-right font-medium text-red-600">{{ $docsAEliminar }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    El concurso todavía no puede finalizar porque no ha pasado la fecha de cierre
                    @if ($concurso->fecha_cierre < now()->addDays(7))
                        <button wire:click="mailsRecordatorios()" class="bg-blue-500 text-white font-bold text-lg w-full py-3 rounded-lg shadow hover:bg-blue-600">
                            Mandar notificación de que está por cerrar
                        </button>
                    @endif
                    <p class="text-sm italic mt-4 border-t border-gray-200 pt-2">
                        También puedes reprogramar los emails programados para este concurso en caso de que hayan habido modificaciones. Esta acción no rompe nada.
                    </p>
                    <button wire:click="reprogramarEmails()" class="bg-blue-500 text-white font-bold text-lg w-full py-3 mt-2 rounded-lg shadow hover:bg-blue-600" onclick="handleButtonClick23(this)">
                        Reprogramar Emails
                    </button>
                    <script>
                        function handleButtonClick23(button) {
                            // Deshabilitar el botón
                            button.disabled = true;
                            // Cambiar texto del botón
                            button.innerText = "Reprogramando emails...";
                            // Cambiar estilo (opcional)
                            button.classList.add("cursor-not-allowed", "opacity-50");
                        }
                    </script>
                @endif
            @elseif($concurso->estado->id == 3)
                <button onclick="confirm('¿Estás seguro de querer terminar el concurso?') || event.stopImmediatePropagation();" wire:click="actualizarEstado(4)" class="bg-green-500 text-white font-bold text-lg w-full py-3 rounded-lg shadow hover:bg-green-600">
                    Marcar como Terminado
                </button>
                <p class="text-sm italic mt-4 border-t border-gray-200 pt-2">
                    Finaliza completamente el proceso licitatorio y se da por terminado el concurso.
                </p>
            @endif
            @can('Concursos/Concursos/Anular')
            <div class="bg-gray-100 p-4 mt-4 rounded-lg border-t border-gray-200 pt-2">
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
