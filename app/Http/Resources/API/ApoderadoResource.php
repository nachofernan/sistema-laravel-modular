<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ApoderadoResource extends JsonResource
{
    public function toArray($request)
    {
        if (!$this->activo) {
            return null;
        }

        return [
            'id' => $this->documentos(true)->latest()->first()->id,
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
        ];
    }
} 