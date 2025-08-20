<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            TitoBot - Asistente Virtual
        </h2>
    </x-slot>

    <style>
        .prose strong {
            font-weight: 600;
            color: #1f2937;
        }
        .prose code {
            background-color: #f3f4f6;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-family: 'Courier New', monospace;
        }
        .chat-message {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    <div class="flex h-screen bg-gray-50">
        <!-- Chat Container -->
        <div class="flex-1 flex flex-col">
            <!-- Chat Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                <!-- Mensaje de bienvenida -->
                <div class="flex items-start space-x-3 mb-4 chat-message">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">T</span>
                        </div>
                    </div>
                    <div class="flex-1 bg-white rounded-lg p-4 shadow-sm border max-w-4xl">
                        <div class="text-sm leading-relaxed text-gray-800 prose prose-sm max-w-none">
                            ¡Hola! Soy <strong>TitoBot</strong>, tu asistente virtual en <strong>Buenos Aires Energía S.A.</strong> 
                            <br><br>
                            Estoy aquí para ayudarte con consultas sobre:
                            <br>• <strong>Empleados</strong> y sus datos
                            <br>• <strong>Áreas</strong> y departamentos
                            <br>• <strong>Sedes</strong> y ubicaciones
                            <br>• <strong>Internos telefónicos</strong>
                            <br>• <strong>Información laboral</strong> en general
                            <br><br>
                            ¿En qué puedo ayudarte hoy?
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Input Area -->
            <div class="border-t bg-white p-4">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <textarea 
                            id="message-input" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Escribe tu mensaje aquí..."
                            rows="2"
                            maxlength="1000"
                        ></textarea>
                    </div>
                    <button 
                        id="send-button"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-2 text-xs text-gray-500 text-right">
                    <span id="char-count">0</span>/1000 caracteres
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const chatMessages = document.getElementById('chat-messages');
            const charCount = document.getElementById('char-count');

            // Función para formatear el texto con markdown básico
            function formatMessage(text) {
                // Convertir saltos de línea en <br>
                text = text.replace(/\n/g, '<br>');
                
                // Convertir **texto** en <strong>texto</strong>
                text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                
                // Convertir *texto* en <em>texto</em>
                text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
                
                // Convertir `texto` en <code>texto</code>
                text = text.replace(/`(.*?)`/g, '<code class="bg-gray-100 px-1 py-0.5 rounded text-sm">$1</code>');
                
                return text;
            }

            // Función para agregar mensaje al chat
            function addMessage(content, isUser = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex items-start space-x-3 mb-4 chat-message';
                
                if (isUser) {
                    messageDiv.innerHTML = `
                        <div class="flex-1"></div>
                        <div class="flex-1 bg-blue-500 text-white rounded-lg p-4 shadow-sm max-w-3xl">
                            <div class="text-sm leading-relaxed">${formatMessage(content)}</div>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">U</span>
                            </div>
                        </div>
                    `;
                } else {
                    messageDiv.innerHTML = `
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">T</span>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg p-4 shadow-sm border max-w-4xl">
                            <div class="text-sm leading-relaxed text-gray-800 prose prose-sm max-w-none">
                                ${formatMessage(content)}
                            </div>
                        </div>
                    `;
                }
                
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Función para mostrar indicador de carga
            function showLoading() {
                const loadingDiv = document.createElement('div');
                loadingDiv.id = 'loading-message';
                loadingDiv.className = 'flex items-start space-x-3';
                loadingDiv.innerHTML = `
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">T</span>
                        </div>
                    </div>
                    <div class="flex-1 bg-white rounded-lg p-4 shadow-sm border">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                `;
                chatMessages.appendChild(loadingDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // Función para remover indicador de carga
            function hideLoading() {
                const loadingMessage = document.getElementById('loading-message');
                if (loadingMessage) {
                    loadingMessage.remove();
                }
            }

            // Función para enviar mensaje
            async function sendMessage() {
                const message = messageInput.value.trim();
                if (!message) return;

                // Agregar mensaje del usuario
                addMessage(message, true);
                
                // Limpiar input
                messageInput.value = '';
                charCount.textContent = '0';
                sendButton.disabled = true;

                // Mostrar loading
                showLoading();

                try {
                    const response = await fetch('{{ route("chat.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: message })
                    });

                    const data = await response.json();
                    
                    // Remover loading
                    hideLoading();

                    if (data.reply) {
                        addMessage(data.reply);
                    } else if (data.error) {
                        addMessage('Lo siento, hubo un error al procesar tu mensaje. Por favor, intenta de nuevo.');
                    }
                } catch (error) {
                    hideLoading();
                    addMessage('Lo siento, hubo un error de conexión. Por favor, verifica tu conexión e intenta de nuevo.');
                }
            }

            // Event listeners
            messageInput.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;
                sendButton.disabled = length === 0;
            });

            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            sendButton.addEventListener('click', sendMessage);
        });
    </script>
</x-app-layout> 