<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactoConcursoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'concurso_id' => $this->concurso_id,
            'tipo' => $this->tipo,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
} 