<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ApoderadoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'documentos_validados' => DocumentoResource::collection($this->whenLoaded('documentosValidados')),
            // Agrega otros campos relevantes
        ];
    }
} 