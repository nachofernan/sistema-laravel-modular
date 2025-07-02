<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        
        // Obtener el historial de conversación de la sesión
        $conversation = session('chat_history', []);
        
        // Agregar el mensaje del usuario al historial
        $conversation[] = ['role' => 'user', 'content' => $message];
        
        $client = new Client();
        
        try {
            // Preparar mensajes con herramientas disponibles
            $tools = ToolsController::getAvailableTools();
            $toolsDescription = $this->formatToolsForAI($tools);
            
            $systemPrompt = "
            Eres un asistente útil que responde en español de manera amigable y concisa. 
            Mantenés el contexto de toda la conversación. 
            Tu nombre es TitoBot. Trabajamos en una empresa llamada Buenos Aires Energía S.A. (BAESA). 
            Estás para asistir a las personas en temas laborales, técnicos o administrativos dentro de la empresa. 
            El usuario se llama " . Auth::user()->realname . ". Intenta llamarlo por el nombre de pila. Trata de identificar su género.
            Primero aparece el apellido y luego el nombre, pero si no lo podés identificar, preguntás al usuario cómo prefiere que lo llames.
            No tenés acceso a datos confidenciales ni contraseñas. Siempre terminás tus respuestas con \"Sos un Tigre\" o algo que lo enaltezca (en relación al género).

            HERRAMIENTAS DISPONIBLES:
            {$toolsDescription}

            REGLAS IMPORTANTES:
            1. Si la consulta requiere buscar información específica sobre empleados, departamentos, internos o emails, DEBES usar las herramientas disponibles.
            2. Cuando uses herramientas, responde SOLO con el JSON, sin texto adicional.
            3. Cuando no uses herramientas (conversación normal), termina con 'Sos un tigre'.

            FORMATOS PARA HERRAMIENTAS:

            Para UNA herramienta (responde SOLO esto, sin más texto):
            {\"action\": \"use_tool\", \"tool\": \"nombre_herramienta\", \"parameters\": {parametros}, \"context\": \"explicación de qué buscas\"}

            Para MÚLTIPLES herramientas (responde SOLO esto, sin más texto):
            {\"action\": \"use_multiple_tools\", \"user_request\": \"pedido original del usuario\", \"tools\": [
                {\"tool\": \"herramienta1\", \"parameters\": {parametros1}, \"context\": \"qué busco con esta\"},
                {\"tool\": \"herramienta2\", \"parameters\": {parametros2}, \"context\": \"qué busco con esta\"}
            ]}

            EJEMPLOS:
            Usuario: 'Traeme todos los usuarios de Contabilidad'
            Tu respuesta: {\"action\": \"use_tool\", \"tool\": \"buscar_usuarios_por_area\", \"parameters\": {\"area\": \"contabilidad\"}, \"context\": \"busco todos los empleados del área de contabilidad\"}

            Usuario: 'Hola, ¿cómo estás?'
            Tu respuesta: ¡Hola! Estoy bien, listo para ayudarte con cualquier consulta sobre empleados. Sos un tigre.";
            
            $messages = [
                ['role' => 'system', 'content' => $systemPrompt]
            ];
            
            // Agregar todo el historial de conversación
            $messages = array_merge($messages, $conversation);
            
            $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek/deepseek-r1-0528:free',
                    'messages' => $messages
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $reply = $data['choices'][0]['message']['content'];
            
            // Verificar si la IA quiere usar una herramienta
            $finalReply = $this->processAIResponse($reply);
            
            // Agregar la respuesta del asistente al historial
            $conversation[] = ['role' => 'assistant', 'content' => $finalReply];
            
            // Guardar el historial actualizado en la sesión
            session(['chat_history' => $conversation]);

            return response()->json(['reply' => $finalReply]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    private function formatToolsForAI($tools)
    {
        $formatted = "Lista de herramientas disponibles:\n";
        foreach ($tools as $tool) {
            $formatted .= "- {$tool['name']}: {$tool['description']}\n";
            if (!empty($tool['parameters'])) {
                $formatted .= "  Parámetros requeridos:\n";
                foreach ($tool['parameters'] as $param => $desc) {
                    $formatted .= "    - {$param}: {$desc}\n";
                }
            } else {
                $formatted .= "  (No requiere parámetros)\n";
            }
        }
        return $formatted;
    }

    private function processAIResponse($reply)
    {
        // Limpiar la respuesta y buscar JSON
        $reply = trim($reply);
        
        // Buscar JSON en la respuesta
        if (preg_match('/\{.*"action".*\}/s', $reply, $matches)) {
            $jsonString = $matches[0];
            $decoded = json_decode($jsonString, true);
            
            if ($decoded && isset($decoded['action'])) {
                $availableTools = array_column(ToolsController::getAvailableTools(), 'name');
                
                if ($decoded['action'] === 'use_tool') {
                    // Verificar si la herramienta existe
                    if (!in_array($decoded['tool'], $availableTools)) {
                        return "Error: La herramienta '{$decoded['tool']}' no está disponible.";
                    }
                    $toolResult = $this->executeTool($decoded['tool'], $decoded['parameters'] ?? []);
                    return $this->askAIToProcessResult($decoded['tool'], $toolResult, $decoded['context'] ?? '');
                } 
                elseif ($decoded['action'] === 'use_multiple_tools') {
                    $results = [];
                    foreach ($decoded['tools'] as $tool) {
                        if (!in_array($tool['tool'], $availableTools)) {
                            $results[] = [
                                'tool' => $tool['tool'],
                                'result' => "Error: La herramienta '{$tool['tool']}' no está disponible.",
                                'context' => $tool['context'] ?? ''
                            ];
                            continue;
                        }
                        $result = $this->executeTool($tool['tool'], $tool['parameters'] ?? []);
                        $results[] = [
                            'tool' => $tool['tool'],
                            'result' => $result,
                            'context' => $tool['context'] ?? ''
                        ];
                    }
                    return $this->askAIToProcessMultipleResults($results, $decoded['user_request'] ?? '');
                }
            }
        }
        
        // Si no es un comando de herramienta, devolver la respuesta normal
        return $reply;
    }

    private function executeTool($toolName, $parameters)
    {
        $toolsController = new ToolsController();
        
        $request = new Request([
            'tool' => $toolName,
            'parameters' => $parameters
        ]);
        $response = $toolsController->executeTool($request);
        $data = json_decode($response->getContent(), true);
        if ($data['success'] ?? false) {
            return $this->formatToolResponse($toolName, $data['data'], $data['total'] ?? 0);
        } else {
            return "Error al ejecutar la consulta: " . ($data['error'] ?? 'Error desconocido');
        }
    }

    private function formatToolResponse($toolName, $data, $total)
    {
        $response = "";
        
        switch ($toolName) {
            case 'buscar_usuario_por_nombre':
                $response = "Encontré {$total} usuarios:\n\n";
                foreach ($data as $user) {
                    $response .= "• {$user['nombre_real']} - {$user['email']} (Interno: {$user['interno']}, Legajo: {$user['legajo']}, Área: {$user['area']}, Sede: {$user['sede']})\n";
                }
                break;
                
            case 'buscar_usuario_por_legajo':
                $response = "Usuario encontrado:\n\n";
                $response .= "• {$data['nombre_real']} - {$data['email']} (Interno: {$data['interno']}, Área: {$data['area']}, Sede: {$data['sede']}, Roles: " . implode(', ', $data['roles']) . ")\n";
                break;
                
            case 'buscar_usuarios_por_area':
                $response = "Encontré {$total} usuarios en el área:\n\n";
                foreach ($data as $user) {
                    $response .= "• {$user['nombre']} - {$user['email']} (Interno: {$user['interno']}, Sede: {$user['sede']})\n";
                }
                break;
                
            case 'buscar_usuarios_por_sede':
                $response = "Encontré {$total} usuarios en la sede:\n\n";
                foreach ($data as $user) {
                    $response .= "• {$user['nombre']} - {$user['email']} (Interno: {$user['interno']}, Área: {$user['area']})\n";
                }
                break;
                
            case 'buscar_interno_por_nombre':
                if ($total > 0) {
                    $response = "Encontré {$total} coincidencias:\n\n";
                    foreach ($data as $user) {
                        $response .= "• {$user['nombre']} - Interno: {$user['interno']} ({$user['email']}, Área: {$user['area']})\n";
                    }
                } else {
                    $response = "No encontré ningún empleado con ese nombre.";
                }
                break;
                
            case 'listar_areas':
                $response = "Áreas disponibles ({$total}):\n\n";
                foreach ($data as $area) {
                    $response .= "• {$area['nombre']} (Usuarios: {$area['usuarios_count']}, Área padre: {$area['area_padre']})\n";
                }
                break;
                
            case 'listar_sedes':
                $response = "Sedes disponibles ({$total}):\n\n";
                foreach ($data as $sede) {
                    $response .= "• {$sede['nombre']} (Usuarios: {$sede['usuarios_count']})\n";
                }
                break;
                
            case 'obtener_estructura_area':
                $response = "Estructura del área {$data['area']['nombre']}:\n\n";
                $response .= "• Área: {$data['area']['nombre']}\n";
                if ($data['area_padre']) {
                    $response .= "• Área padre: {$data['area_padre']['nombre']}\n";
                }
                $response .= "• Usuarios: {$data['usuarios_count']}\n";
                if (!empty($data['areas_hijas'])) {
                    $response .= "• Áreas hijas:\n";
                    foreach ($data['areas_hijas'] as $hija) {
                        $response .= "  - {$hija['nombre']} (Usuarios: {$hija['usuarios_count']})\n";
                    }
                }
                break;
                
            case 'buscar_usuarios_con_roles':
                $response = "Usuarios con roles específicos ({$total}):\n\n";
                foreach ($data as $user) {
                    $response .= "• {$user['nombre']} - {$user['email']} (Área: {$user['area']}, Sede: {$user['sede']}, Roles: " . implode(', ', $user['roles']) . ")\n";
                }
                break;
                
            case 'estadisticas_usuarios':
                $response = "Estadísticas de usuarios:\n\n";
                $response .= "• Total usuarios: {$data['resumen']['total_usuarios']}\n";
                $response .= "• Usuarios con interno: {$data['resumen']['usuarios_con_interno']}\n";
                $response .= "• Usuarios sin interno: {$data['resumen']['usuarios_sin_interno']}\n";
                $response .= "• Total áreas: {$data['resumen']['total_areas']}\n";
                $response .= "• Total sedes: {$data['resumen']['total_sedes']}\n\n";
                $response .= "Usuarios por área:\n";
                foreach ($data['usuarios_por_area'] as $area) {
                    $response .= "• {$area['area']}: {$area['usuarios']} usuarios\n";
                }
                $response .= "\nUsuarios por sede:\n";
                foreach ($data['usuarios_por_sede'] as $sede) {
                    $response .= "• {$sede['sede']}: {$sede['usuarios']} usuarios\n";
                }
                break;
        }
        
        return $response;
    }

    private function askAIToProcessResult($toolName, $toolResult, $context)
    {
        $client = new Client();
        
        $prompt = "El usuario me pidió información y ejecuté la herramienta '{$toolName}' con el contexto: '{$context}'.

Resultado obtenido:
{$toolResult}

Por favor, procesa esta información y devuelve una respuesta natural y útil en español. Si no hay resultados o hay problemas, sugiere alternativas. Sé conversacional y amigable. Terminá tu respuesta con \"Sos un Tigre\" o algo que lo enaltezca (en relación al género).";

        try {
            $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek/deepseek-r1-0528:free',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Eres un asistente útil que procesa resultados de consultas y los presenta de manera amigable en español.'],
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'];
            
        } catch (\Exception $e) {
            return $toolResult . "\n\n(Error al procesar la respuesta con IA: " . $e->getMessage() . ")";
        }
    }

    private function askAIToProcessMultipleResults($results, $userRequest)
    {
        $client = new Client();
        
        $resultsText = "";
        foreach ($results as $result) {
            $resultsText .= "Herramienta: {$result['tool']}\n";
            $resultsText .= "Contexto: {$result['context']}\n";
            $resultsText .= "Resultado:\n{$result['result']}\n\n";
        }
        
        $prompt = "El usuario me pidió: '{$userRequest}'

Para responder, ejecuté varias herramientas y obtuve estos resultados:

{$resultsText}

Por favor, analiza todos estos resultados y devuelve una respuesta integral, natural y útil en español. Combina la información, señala patrones o insights interesantes, y si hay información faltante o problemas, sugiérelos. Sé conversacional y organiza bien la información. Terminá tu respuesta con \"Sos un Tigre\" o algo que lo enaltezca (en relación al género).";

        try {
            $response = $client->post('https://openrouter.ai/api/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'deepseek/deepseek-r1-0528:free',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Eres un asistente útil que analiza múltiples resultados de consultas y los presenta de manera integral y amigable en español.'],
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['choices'][0]['message']['content'];
            
        } catch (\Exception $e) {
            $fallback = "Resultados obtenidos:\n\n";
            foreach ($results as $result) {
                $fallback .= $result['result'] . "\n\n";
            }
            return $fallback . "(Error al procesar con IA: " . $e->getMessage() . ")";
        }
    }
    
    public function clearHistory()
    {
        session()->forget('chat_history');
        return response()->json(['message' => 'Historial borrado']);
    }
}