<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentoResource extends JsonResource
{
    public function toArray($request)
    {
        $vencimiento = $this->documentoTipo->vencimiento && $this->vencimiento ? $this->vencimiento->format('Y-m-d') : null;
        return [
            'id' => $this->id,
            'nombre' => $this->documentoTipo->nombre,
            'vencimiento' => $vencimiento,
        ];
    }
} 