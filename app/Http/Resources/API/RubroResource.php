<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class RubroResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rubro' => $this->nombre,
            'subrubros' => SubrubroResource::collection($this->whenLoaded('subrubros')),
        ];
    }
} 