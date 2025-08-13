<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfertaDocumentoResource extends JsonResource
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
            'documento_tipo_id' => $this->documento_tipo_id,
            'documento_proveedor_id' => $this->documento_proveedor_id,
            'archivo' => $this->archivo,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'file_storage' => $this->file_storage,
            'user_id_created' => $this->user_id_created,
            'comentarios' => $this->comentarios,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'documento_tipo' => $this->whenLoaded('documentoTipo', function() {
                return [
                    'id' => $this->documentoTipo->id,
                    'nombre' => $this->documentoTipo->nombre,
                    'descripcion' => $this->documentoTipo->descripcion,
                ];
            }),
            'documento_proveedor' => $this->whenLoaded('documentoProveedor', function() {
                return [
                    'id' => $this->documentoProveedor->id,
                    'archivo' => $this->documentoProveedor->archivo,
                    'validado' => $this->documentoProveedor->validacion ? $this->documentoProveedor->validacion->validado : false,
                ];
            }),
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