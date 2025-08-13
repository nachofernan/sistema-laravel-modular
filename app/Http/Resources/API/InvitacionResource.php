<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class InvitacionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'concurso_id' => $this->concurso_id,
            'proveedor_id' => $this->proveedor_id,
            'intencion' => $this->intencion,
            'fecha_envio' => $this->fecha_envio?->format('Y-m-d'),
            'documentos' => ConcursoDocumentoResource::collection($this->whenLoaded('documentos')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
} 