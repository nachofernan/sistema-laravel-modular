<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class SubrubroResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subrubro' => $this->nombre,
        ];
    }
} 