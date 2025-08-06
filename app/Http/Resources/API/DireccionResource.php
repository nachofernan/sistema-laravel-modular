<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class DireccionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'calle' => $this->calle,
            'altura' => $this->altura,
            'piso' => $this->piso,
            'departamento' => $this->departamento,
            'ciudad' => $this->ciudad,
            'codigopostal' => $this->codigopostal,
            'provincia' => $this->provincia,
            'pais' => $this->pais,
        ];
    }
} 