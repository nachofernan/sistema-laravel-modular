<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class DireccionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'calle' => $this->calle,
            'numero' => $this->numero,
            'localidad' => $this->localidad,
            'provincia' => $this->provincia,
            // Agrega otros campos relevantes
        ];
    }
} 