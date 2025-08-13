<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentoAdicionalResource extends JsonResource
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
            'invitacion_id' => $this->invitacion_id,
            'documento_tipo_id' => $this->documento_tipo_id, // Será null para documentos adicionales
            'documento_proveedor_id' => $this->documento_proveedor_id,
            'archivo' => $this->archivo,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'file_storage' => $this->file_storage,
            'user_id_created' => $this->user_id_created,
            'comentarios' => $this->comentarios,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'es_adicional' => true, // Siempre será true para este resource
            'fue_subido_por_proveedor' => is_null($this->user_id_created),
            'fue_ingresado_por_empresa' => !is_null($this->user_id_created),
            'creador' => $this->whenLoaded('creador', function() {
                return [
                    'id' => $this->creador->id,
                    'name' => $this->creador->name,
                ];
            }),
            'media_id' => $this->getFirstMedia('archivos') ? $this->getFirstMedia('archivos')->id : null,
        ];
    }
} 