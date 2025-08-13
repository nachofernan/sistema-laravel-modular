<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Proveedores\Proveedor;
use App\Models\Proveedores\Documento;
use Illuminate\Support\Facades\Log;

class DocumentoTipoResource extends JsonResource
{
    


    public function toArray($request)
    {
        // Preparar datos del tipo de documento del proveedor
        $tipoDocumentoProveedor = null;
        if ($this->tipo_documento_proveedor_id && $this->tipo_documento_proveedor) {
            $tipoDocumentoProveedor = [
                'id' => $this->tipo_documento_proveedor->id,
                'nombre' => $this->tipo_documento_proveedor->nombre,
                'codigo' => $this->tipo_documento_proveedor->codigo,
                'vencimiento' => $this->tipo_documento_proveedor->vencimiento,
            ];
        }

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'de_concurso' => $this->de_concurso,
            'obligatorio' => $this->obligatorio,
            'tipo_documento_proveedor_id' => $this->tipo_documento_proveedor_id,
            'tipo_documento_proveedor' => $tipoDocumentoProveedor,
        ];
    }
} 