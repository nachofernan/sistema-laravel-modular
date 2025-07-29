<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cuit' => $this->cuit,
            'razonsocial' => $this->razonsocial,
            'correo' => $this->correo,
            'telefono' => $this->telefono,
            'webpage' => $this->webpage,
            'contactos' => ContactoResource::collection($this->whenLoaded('contactos')),
            'direcciones' => DireccionResource::collection($this->whenLoaded('direcciones')),
            'documentos' => DocumentoResource::collection($this->whenLoaded('documentos')),
            'subrubros' => SubrubroResource::collection($this->whenLoaded('subrubros')),
            'apoderados' => ApoderadoResource::collection($this->whenLoaded('apoderados')),
        ];
    }
} 