<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
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
</div>
