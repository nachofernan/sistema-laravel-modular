<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SplitUserNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:split-user-names {--limit= : Limit the number of users to process} {--chunk=10 : Number of users per API call}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Splits user->realname into nombre and apellido using Groq API (llama-3.1-8b-instant)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = env('GROQ_API');

        if (!$apiKey) {
            $this->error('GROQ_API not found in .env');
            return 1;
        }

        $query = User::whereNull('nombre')->whereNull('apellido')->whereNotNull('realname');

        if ($limit = $this->option('limit')) {
            $query->limit($limit);
        }

        $totalUsers = $query->count();

        if ($totalUsers === 0) {
            $this->info('No users found to process.');
            return 0;
        }

        $this->info("Processing {$totalUsers} users...");

        $chunkSize = (int) $this->option('chunk');
        $bar = $this->output->createProgressBar($totalUsers);
        $bar->start();

        $query->chunk($chunkSize, function ($users) use ($apiKey, $bar) {
            $payload = $users->map(fn($user) => [
                'id' => $user->id,
                'realname' => $user->realname,
            ])->toArray();

            $response = $this->callGroq($payload, $apiKey);

            if ($response && is_array($response)) {
                foreach ($response as $item) {
                    if (isset($item['id'], $item['nombre'])) {
                        User::where('id', $item['id'])->update([
                            'nombre' => $item['nombre'],
                            'apellido' => $item['apellido'] ?? '',
                        ]);
                    }
                }
            }

            $bar->advance($users->count());
            
            // Add a delay to avoid rate limits (TPM: 6000 is very low)
            sleep(5);
        });

        $bar->finish();
        $this->newLine();
        $this->info('Done!');

        return 0;
    }

    private function callGroq(array $payload, string $apiKey)
    {
        $prompt = "Eres un experto en nombres hispanos. Recibirás un JSON con una lista de usuarios que tienen 'id' y 'realname'. 
        El campo 'realname' está en formato 'APELLIDO NOMBRE' (generalmente el apellido primero, pero puede haber varios apellidos y nombres).
        Tu tarea es separar con precisión el nombre (o nombres) y el apellido (o apellidos) y devolver EXCLUSIVAMENTE un JSON con este formato: 
        {\"usuarios\": [{\"id\": 1, \"nombre\": \"JUAN CARLOS\", \"apellido\": \"PEREZ GARCIA\"}, ...]}.
        
        Reglas:
        1. Devuelve SOLO el JSON, sin texto adicional, sin bloques de código markdown.
        2. Mantén las mayúsculas del original (generalmente están en mayúsculas).
        3. Si hay ambigüedad, usa tu conocimiento de nombres comunes (ej: 'Martin' puede ser nombre o apellido, pero si está al principio suele ser apellido).
        4. No inventes datos.
        
        Usuarios: " . json_encode($payload);

        try {
            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => [
                        ['role' => 'system', 'content' => 'Eres un procesador de datos que responde solo en JSON.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0,
                    'response_format' => ['type' => 'json_object']
                ]);

            if ($response->failed()) {
                $this->error("\nAPI call failed: " . $response->body());
                return null;
            }

            $content = $response->json('choices.0.message.content');
            $data = json_decode($content, true);

            return $data['usuarios'] ?? $data['users'] ?? (is_array($data) && !isset($data['id']) ? $data : null);

        } catch (\Exception $e) {
            $this->error("\nError: " . $e->getMessage());
            return null;
        }
    }
}
