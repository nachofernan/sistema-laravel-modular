<?php

namespace App\Mail\Concursos\Traits;

use App\Models\Concursos\Concurso;
use Illuminate\Support\Facades\Log;

trait ConcursoMailableTrait
{
    public function __construct(
        public $entidad, 
        public string $destinatario = '',
        public ?array $datosDestinatario = null
    ) {
    }

    /**
     * Obtener el ID del concurso desde la entidad
     */
    protected function getConcursoId(): int
    {
        if ($this->entidad instanceof Concurso) {
            return $this->entidad->id;
        }
        
        return $this->entidad->concurso_id ?? 0;
    }

    /**
     * Obtener el link correcto según el destinatario y entorno
     */
    public function getLinkConcurso(): string
    {
        $tipo = $this->datosDestinatario['tipo'] ?? 'interno';
        $concursoId = $this->getConcursoId();
        
        if ($tipo === 'interno') {
            // Link interno según entorno
            $baseUrl = config('app.env') === 'production' 
                ? 'http://172.17.8.80/plataforma'
                : 'http://172.17.9.231/plataforma';
                
            return "{$baseUrl}/concursos/concursos/{$concursoId}";
        }
        
        // Link externo (proveedores)
        return "https://buenosairesenergia.com.ar/registroproveedores/concursos/{$concursoId}";
    }

    /**
     * Datos comunes para la vista
     */
    protected function viewData(): array
    {
        Log::info('viewData', $this->datosDestinatario);
        return [
            'linkConcurso' => $this->getLinkConcurso(),
            'nombre' => $this->datosDestinatario['nombre'] ?? 'Usuario',
            'tipo' => $this->datosDestinatario['tipo'] ?? 'interno',
            'cuit' => $this->datosDestinatario['cuit'] ?? null,
            'empresa' => $this->datosDestinatario['empresa'] ?? null,
            'cargo' => $this->datosDestinatario['cargo'] ?? null,
        ];
    }
}
