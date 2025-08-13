<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ProrrogaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'concurso_id' => $this->concurso_id,
            'fecha_anterior' => $this->fecha_anterior?->format('Y-m-d H:i:s'),
            'fecha_actual' => $this->fecha_actual?->format('Y-m-d H:i:s'),
            'motivo' => $this->motivo,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
} 