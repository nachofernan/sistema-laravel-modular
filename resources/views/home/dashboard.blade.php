<x-app-layout>
    <div class="w-full xl:w-12/12 mb-12 xl:mb-0 px-4 mx-auto mt-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6">
            @if (session('mensaje_verde'))
            <div class="block w-full overflow-x-auto my-2">
                <div class="bg-green-600 w-1/2 py-3 px-10 border border-green-700 rounded text-white font-bold text-center m-auto"
                onclick="this.classList.add('hidden');">
                    {{ session('mensaje_verde') }}
                </div>
            </div>
            @endif
            <div class="block w-full overflow-x-auto">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-6">
                        <div class="bg-white border shadow rounded p-4 grid grid-cols-12 gap-4">
                            <div class="col-span-7">
                                <div class="subtitulo-show">
                                    Nuevo Ticket
                                </div>
                                <form id="ticketForm" action="{{ route('home.tickets.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="pb-2 text-sm">
                                        Categoria
                                        <select id="categoria" name="categoria_id" class="input-full">
                                            @foreach ($ticket_categorias as $categoria)
                                                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <textarea name="notas" rows="5" placeholder="Mensaje..." required class="input-full"></textarea>
                                    <input type="file" id="documento" name="documento" class="input-full">
                                    <button type="submit" class="boton-celeste text-sm mt-2" id="submitBtn" 
                                    {{-- onclick="this.form.submit(); this.classList.add('bg-gray-200'); this.classList.add('hover:bg-gray-200'); this.innerText='Enviando...'; this.disabled=true" --}}
                                    >Enviar Ticket</button>
                                </form>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        const form = document.getElementById("ticketForm");
                                        const categoriaSelect = document.getElementById("categoria");
                                        const fileInput = document.getElementById("documento");
                                        const submitBtn = document.getElementById("submitBtn");

                                        categoriaSelect.addEventListener("change", function () {
                                            fileInput.required = (this.value === "9");
                                        });

                                        form.addEventListener("submit", function (event) {
                                            if (categoriaSelect.value === "9" && !fileInput.files.length) {
                                                alert("Debes adjuntar un archivo para esta categoría.");
                                                event.preventDefault(); // Evita el envío del formulario
                                                return;
                                            }

                                            // Deshabilitar el botón después de hacer clic
                                            submitBtn.disabled = true;
                                            submitBtn.classList.add("bg-gray-200", "hover:bg-gray-200");
                                            submitBtn.innerText = "Enviando...";
                                        });
                                    });
                                </script>                            </div>
                            <div class="col-span-5">
                                <div class="pb-3 mb-3">
                                    <div class="subtitulo-show">
                                        Tickets Abiertos:
                                    </div>
                                    @foreach (Auth::user()->ticketsAbiertos() as $ticket)
                                        <a href="{{ route('home.tickets.show', $ticket) }}"
                                            class="block my-1 rounded border w-full text-sm hover:shadow-lg hover:bg-gray-100">
                                            <div class="font-bold bg-gray-800 text-xs text-white rounded-t px-2 py-1">
                                                #{{ $ticket->codigo }}
                                                @if ($ticket->mensajesNuevos())
                                                    <div
                                                        class="font-bold bg-red-800 text-white text-xs rounded px-2 py-0 float-right">
                                                        Nuevo Mensaje</div>
                                                @endif
                                            </div>
                                            <div class="px-2 py-2">
                                                {{ $ticket->categoria->nombre }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                                <div class="pb-3 mb-3">
                                    <div class="subtitulo-show">
                                        Tickets Cerrados:
                                    </div>
                                    @foreach (Auth::user()->ticketsCerrados() as $ticket)
                                        <a href="{{ route('home.tickets.show', $ticket) }}"
                                            class="block my-1 rounded border w-full text-sm hover:shadow-lg hover:bg-gray-100">
                                            <div class="font-bold bg-gray-400 text-xs text-white rounded-t px-2 py-1">
                                                #{{ $ticket->codigo }}
                                            </div>
                                            <div class="px-2 py-2">
                                                {{ $ticket->categoria->nombre }}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <div class="bg-white border shadow rounded p-4">
                            <div class="subtitulo-show">
                                Mi inventario:
                            </div>
                            <table class="table-index">
                                <thead>
                                    <th class="th-index">Ícono</th>
                                    <th class="th-index">Código</th>
                                    <th class="th-index">Elemento</th>
                                    <th class="th-index">Entrega</th>
                                    <th class="th-index">Firma</th>
                                </thead>
                                <tbody>
                                    @foreach (Auth::user()->elementos() as $elemento)
                                        <tr class="hover:bg-gray-300">
                                            <td class="td-index">{!! $elemento->categoria->icono !!}</td>
                                            <td class="td-index">{{ $elemento->codigo }}</td>
                                            <td class="td-index">{{ $elemento->categoria->nombre }}</td>
                                            <td class="td-index">
                                                @if ($elemento->entregaActual())
                                                    {{ Carbon\Carbon::create($elemento->entregaActual()->fecha_entrega)->format('d-m-Y') }}
                                                @endif
                                            </td>
                                            <td class="td-index">
                                                @if (
                                                    $elemento->entregaActual() &&
                                                        $elemento->entregaActual()->user->id == Auth::user()->id &&
                                                        !$elemento->entregaActual()->fecha_devolucion)
                                                    @if ($elemento->entregaActual()->fecha_firma)
                                                            Firmado:
                                                            {{ Carbon\Carbon::create($elemento->entregaActual()->fecha_firma)->format('d-m-Y') }}
                                                    @else
                                                        @livewire('home.dashboard.inventario.firmar-entrega', ['elemento' => $elemento], key($elemento->id . microtime(true)))
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                         <div class="bg-white border shadow rounded p-4 mt-2">
                            <div class="subtitulo-show">
                                Chat Assistant
                            </div>
                            
                            <!-- Chat Container -->
                            <div id="chat-container" class="p-3 mb-3 h-64 overflow-y-auto bg-white">
                                <div class="text-gray-500 text-sm italic">¡Hola, {{ Auth::user()->realname }}! Soy TitoBot, ¿En qué puedo ayudarte hoy?</div>
                            </div>
                            
                            <!-- Chat Input Form -->
                            <form id="chatForm">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" 
                                           id="message-input" 
                                           name="message" 
                                           placeholder="Escribe tu mensaje..." 
                                           required 
                                           class="input-full flex-1"
                                           autocomplete="off">
                                    <button type="submit" 
                                            class="boton-celeste text-sm" 
                                            id="sendBtn">
                                        Enviar
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Clear History Button -->
                            <button type="button" 
                                    class="text-sm text-gray-500 hover:text-gray-700 mt-2 underline" 
                                    id="clearBtn">
                                Limpiar conversación
                            </button>
                        </div>
                        
                        <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const chatContainer = document.getElementById('chat-container');
                            const messageInput = document.getElementById('message-input');
                            const chatForm = document.getElementById('chatForm');
                            const sendBtn = document.getElementById('sendBtn');
                            const clearBtn = document.getElementById('clearBtn');
                        
                            function addMessage(message, isUser = false) {
                                const div = document.createElement('div');
                                div.className = `mb-2 p-2 rounded-lg max-w-xs ${isUser ? 'ml-auto bg-blue-100 text-blue-800' : 'mr-auto bg-gray-100 text-gray-800'}`;
                                div.style.wordWrap = 'break-word';
                                
                                if (isUser) {
                                    div.textContent = message;
                                } else {
                                    // Convertir markdown básico a HTML
                                    let htmlMessage = message
                                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')  // **negrita**
                                        .replace(/\*(.*?)\*/g, '<em>$1</em>')              // *cursiva*
                                        .replace(/`(.*?)`/g, '<code style="background-color: #f1f1f1; padding: 2px 4px; border-radius: 3px;">$1</code>')  // `código`
                                        .replace(/\n/g, '<br>');                           // saltos de línea
                                    
                                    div.innerHTML = htmlMessage;
                                }
                                
                                chatContainer.appendChild(div);
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                            }
                        
                            function addThinkingMessage() {
                                const div = document.createElement('div');
                                div.className = 'mb-2 p-2 rounded-lg max-w-xs mr-auto bg-gray-100 text-gray-800';
                                div.id = 'thinking-message';
                                div.style.wordWrap = 'break-word';
                                div.innerHTML = '<em style="color: #666; font-style: italic; font-size: 12px;">Pensando...</em>';
                                chatContainer.appendChild(div);
                                chatContainer.scrollTop = chatContainer.scrollHeight;
                            }
                        
                            function removeThinkingMessage() {
                                const thinkingMsg = document.getElementById('thinking-message');
                                if (thinkingMsg) {
                                    thinkingMsg.remove();
                                }
                            }
                        
                            function sendMessage() {
                                const message = messageInput.value.trim();
                                if (!message) return;
                        
                                // Agregar mensaje del usuario
                                addMessage(message, true);
                                messageInput.value = '';
                        
                                // Mostrar "pensando..."
                                addThinkingMessage();
                        
                                // Deshabilitar botón
                                sendBtn.disabled = true;
                                sendBtn.textContent = 'Enviando...';
                                sendBtn.classList.add('bg-gray-200', 'hover:bg-gray-200');
                        
                                // Enviar al servidor
                                fetch('{{ url("/chat/send") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ message: message })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Quitar "pensando..."
                                    removeThinkingMessage();
                                    
                                    if (data.reply) {
                                        addMessage(data.reply);
                                    } else {
                                        addMessage('Error: ' + (data.error || 'No se pudo obtener respuesta'));
                                    }
                                })
                                .catch(error => {
                                    // Quitar "pensando..." en caso de error también
                                    removeThinkingMessage();
                                    addMessage('Error de conexión: ' + error.message);
                                })
                                .finally(() => {
                                    // Rehabilitar botón
                                    sendBtn.disabled = false;
                                    sendBtn.textContent = 'Enviar';
                                    sendBtn.classList.remove('bg-gray-200', 'hover:bg-gray-200');
                                });
                            }
                        
                            function clearHistory() {
                                fetch('{{ url("/chat/clear") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    chatContainer.innerHTML = '<div class="text-gray-500 text-sm italic">¡Hola, {{ Auth::user()->realname }}! Soy TitoBot, ¿En qué puedo ayudarte hoy?</div>';
                                })
                                .catch(error => {
                                    console.error('Error al limpiar historial:', error);
                                });
                            }
                        
                            // Event listeners
                            chatForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                sendMessage();
                            });
                        
                            clearBtn.addEventListener('click', clearHistory);
                        
                            // Enter para enviar
                            messageInput.addEventListener('keypress', function(e) {
                                if (e.key === 'Enter' && !e.shiftKey) {
                                    e.preventDefault();
                                    sendMessage();
                                }
                            });
                        });
                        </script>
                        <div class="bg-white border shadow rounded p-4 mt-2 hidden">
                            <div class="subtitulo-show">
                                Mis capacitaciones:
                            </div>
                            <table class="table-index">
                                <thead>
                                    <th class="th-index">Fecha</th>
                                    <th class="th-index">Nombre</th>
                                    <th class="th-index">Ingresar</th>
                                </thead>
                                <tbody>
                                    @foreach (Auth::user()->invitaciones as $invitacion)
                                        <tr class="hover:bg-gray-300">
                                            <td class="td-index">{{ Carbon\Carbon::create($invitacion->capacitacion->fecha)->format('d-m-Y') }}</td>
                                            <td class="td-index">{{ $invitacion->capacitacion->nombre }}</td>
                                            <td class="td-index">
                                                <a href="{{route('home.capacitacions.show', $invitacion->capacitacion)}}" class="link-azul">Ver Capacitación</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
