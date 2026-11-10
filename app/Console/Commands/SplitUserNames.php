<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;

/**
 * Separa `realname` ("APELLIDOS NOMBRES", así viene de RRHH) en `apellido` y `nombre`.
 *
 * El modelo de Groq solo decide cuántas palabras del principio son apellido; el corte se hace
 * sobre el realname original (con espacios normalizados), así no puede inventar ni cambiar
 * mayúsculas. Por defecto solo muestra el resultado; con --aplicar guarda vía $user->update(),
 * para que cada cambio quede en el historial de cambios del usuario.
 *
 * Cubierto por: sin_aplicar_no_guarda_nada, aplicar_corta_sobre_el_realname_original,
 * descarta_cortes_invalidos_e_ids_ajenos, reintenta_cuando_groq_corta_por_limite.
 */
class SplitUserNames extends Command
{
    protected $signature = 'app:split-user-names
        {--aplicar : Guarda los cambios (sin esta opción solo muestra el resultado)}
        {--limit= : Cantidad máxima de usuarios a procesar}
        {--chunk=25 : Usuarios por llamada a la API}';

    protected $description = 'Separa realname en apellido y nombre usando Groq (por defecto, solo vista previa)';

    private const URL = 'https://api.groq.com/openai/v1/chat/completions';

    private const REINTENTOS = 5;

    private const PROMPT = <<<'TXT'
Recibís una lista de personas argentinas con su nombre completo en formato "APELLIDOS NOMBRES": primero el o los apellidos y después el o los nombres de pila.
Para cada una indicá cuántas palabras del principio son apellido ("palabras_apellido").
- Las partículas forman parte del apellido: "De Luca" son 2 palabras, "De La Colina" son 3, "San Martino" son 2.
- Puede haber dos apellidos seguidos (ej: "Fernandez Vega Magali" => 2).
- Si dudás entre apellido o nombre de pila, pensá en nombres de pila comunes en Argentina.
- Algunos nombres vienen truncados o con iniciales al final (ej: "Norber", "L."): son parte de los nombres.
Respondé solo JSON: {"personas": [{"id": 1, "palabras_apellido": 2}, ...]}
TXT;

    public function handle(): int
    {
        if (! config('services.groq.key')) {
            $this->error('Falta GROQ_API en el .env');

            return self::FAILURE;
        }

        // Se toman los usuarios de entrada: el filtro usa las columnas que este comando
        // modifica, así que paginar la query mientras se guarda saltearía usuarios.
        $usuarios = User::whereNull('nombre')->whereNull('apellido')->whereNotNull('realname')
            ->orderBy('id')
            ->when($this->option('limit'), fn ($q, $limite) => $q->limit((int) $limite))
            ->get(['id', 'realname'])
            ->mapWithKeys(fn ($u) => [$u->id => preg_replace('/\s+/', ' ', trim($u->realname))]);

        [$aProcesar, $unaPalabra] = $usuarios->partition(fn ($realname) => str_contains($realname, ' '));

        if ($aProcesar->isEmpty()) {
            $this->info('No hay usuarios para procesar.');
            $this->listarSalteados($unaPalabra);

            return self::SUCCESS;
        }

        $this->info(($this->option('aplicar') ? 'Procesando y GUARDANDO' : 'Vista previa (no guarda nada) de')." {$aProcesar->count()} usuarios con modelo ".config('services.groq.modelo'));

        $resultados = [];
        $sinResultado = [];

        foreach ($aProcesar->chunk(max(1, (int) $this->option('chunk')), true) as $lote) {
            $cortes = $this->pedirCortes($lote);

            foreach ($lote as $id => $realname) {
                $palabras = explode(' ', $realname);
                $k = $cortes[$id] ?? null;

                if ($k === null || $k < 1 || $k >= count($palabras)) {
                    $sinResultado[] = $id;

                    continue;
                }

                $resultados[] = [
                    'id' => $id,
                    'realname' => $realname,
                    'apellido' => implode(' ', array_slice($palabras, 0, $k)),
                    'nombre' => implode(' ', array_slice($palabras, $k)),
                ];
            }
        }

        $this->table(['ID', 'Realname', 'Apellido', 'Nombre'], $resultados);

        if ($this->option('aplicar')) {
            foreach ($resultados as $r) {
                User::find($r['id'])->update(['apellido' => $r['apellido'], 'nombre' => $r['nombre']]);
            }
            $this->info(count($resultados).' usuarios actualizados.');
        } else {
            $this->warn(count($resultados).' usuarios listos. No se guardó nada: correr con --aplicar para guardar.');
        }

        if ($sinResultado !== []) {
            $this->warn(count($sinResultado).' sin resultado válido (volver a correr para reintentarlos): '.implode(', ', $sinResultado));
        }
        $this->listarSalteados($unaPalabra);

        return self::SUCCESS;
    }

    /**
     * Pide a Groq la cantidad de palabras de apellido de cada usuario del lote.
     * Ignora ids que no pertenecen al lote. Reintenta si Groq corta por límite de uso (429).
     *
     * @return array<int, int> id => palabras de apellido
     */
    private function pedirCortes(Collection $lote): array
    {
        $payload = $lote->map(fn ($realname, $id) => ['id' => $id, 'nombre_completo' => $realname])->values()->all();

        for ($intento = 1; $intento <= self::REINTENTOS; $intento++) {
            try {
                $respuesta = Http::withToken(config('services.groq.key'))
                    ->timeout(120)
                    ->post(self::URL, [
                        'model' => config('services.groq.modelo'),
                        'temperature' => 0,
                        'response_format' => ['type' => 'json_object'],
                        'messages' => [
                            ['role' => 'user', 'content' => self::PROMPT."\n\n".json_encode($payload, JSON_UNESCAPED_UNICODE)],
                        ],
                    ]);
            } catch (\Exception $e) {
                $this->error("\nError de conexión: ".$e->getMessage());

                return [];
            }

            if ($respuesta->status() === 429) {
                $espera = (int) ($respuesta->header('retry-after') ?: 30);
                $this->warn("Groq cortó por límite de uso, espero {$espera}s (intento {$intento}/".self::REINTENTOS.')');
                Sleep::for($espera)->seconds();

                continue;
            }

            if ($respuesta->failed()) {
                $this->error("\nFalló la llamada a Groq: ".$respuesta->body());

                return [];
            }

            $datos = json_decode((string) $respuesta->json('choices.0.message.content'), true);

            return collect($datos['personas'] ?? [])
                ->filter(fn ($p) => isset($p['id'], $p['palabras_apellido']) && $lote->has($p['id']))
                ->mapWithKeys(fn ($p) => [(int) $p['id'] => (int) $p['palabras_apellido']])
                ->all();
        }

        $this->error("\nSe agotaron los reintentos para el lote.");

        return [];
    }

    private function listarSalteados(Collection $unaPalabra): void
    {
        if ($unaPalabra->isNotEmpty()) {
            $this->line('Salteados por tener una sola palabra (cargar a mano): '
                .$unaPalabra->map(fn ($r, $id) => "{$id} ({$r})")->implode(', '));
        }
    }
}
