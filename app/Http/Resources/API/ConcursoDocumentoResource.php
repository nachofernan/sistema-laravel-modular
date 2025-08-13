<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConcursoDocumentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'concurso_id' => $this->concurso_id,
            'archivo' => $this->archivo,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'file_storage' => $this->file_storage,
            'user_id_created' => $this->user_id_created,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'creador' => $this->whenLoaded('creador', function() {
                return [
                    'id' => $this->creador->id,
                    'name' => $this->creador->name,
                ];
            }),
            'documento_tipo' => $this->whenLoaded('documentoTipo', function() {
                return [
                    'id' => $this->documentoTipo->id,
                    'nombre' => $this->documentoTipo->nombre,
                    'descripcion' => $this->documentoTipo->descripcion,
                ];
            }),
            'media_id' => $this->getFirstMedia('archivos') ? $this->getFirstMedia('archivos')->id : null,
        ];
    }
} 