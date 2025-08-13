<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Proveedores\Proveedor;
use App\Models\Concursos\Invitacion;
use App\Models\Concursos\OfertaDocumento;
use Illuminate\Support\Facades\Log;

class DocumentoTipoOfertaResource extends JsonResource
{
    protected $proveedor_id;
    protected $concurso_id;
    protected $fecha_cierre;

    public function __construct($resource, $proveedor_id = null, $concurso_id = null, $fecha_cierre = null)
    {
        parent::__construct($resource);
        $this->proveedor_id = $proveedor_id;
        $this->concurso_id = $concurso_id;
        $this->fecha_cierre = $fecha_cierre;
    }

    public function toArray($request)
    {
        // CRÍTICO: Para mantener consistencia auditable, solo se incluyen documentos del proveedor
        // que estaban válidos y cargados ANTES del cierre del concurso
        // Los documentos de oferta se incluyen todos sin restricción de fecha
        if ($this->fecha_cierre) {
            Log::info("DocumentoTipoOfertaResource - Fecha de cierre: " . $this->fecha_cierre . " para tipo: " . $this->nombre);
        }
        
        // Obtener documentos de oferta cargados por el proveedor para este tipo
        // Los documentos de oferta se incluyen todos, sin filtro de fecha, porque son parte de la oferta específica
        $documentosOferta = [];
        if ($this->concurso_id && $this->proveedor_id) {
            $invitacion = Invitacion::where('concurso_id', $this->concurso_id)
                ->where('proveedor_id', $this->proveedor_id)
                ->first();
            
            if ($invitacion) {
                $documentosOferta = OfertaDocumento::where('invitacion_id', $invitacion->id)
                    ->where('documento_tipo_id', $this->id)
                    ->get()
                    ->map(function ($documento) {
                        $media = $documento->getFirstMedia('archivos');
                        return [
                            'id' => $documento->id,
                            'media_id' => $media ? $media->id : null,
                            'archivo' => $documento->archivo,
                            'mimeType' => $documento->mimeType,
                            'extension' => $documento->extension,
                            'encriptado' => $documento->encriptado,
                            'comentarios' => $documento->comentarios,
                            'created_at' => $documento->created_at?->format('Y-m-d H:i:s'),
                            'updated_at' => $documento->updated_at?->format('Y-m-d H:i:s'),
                        ];
                    })->toArray();
            }
        }

        // Obtener el documento del proveedor asociado a este tipo
        // CRÍTICO: Solo incluir documentos válidos y cargados ANTES del cierre del concurso
        $documentoProveedor = null;
        if ($this->proveedor_id && $this->tipo_documento_proveedor_id) {
            $proveedor = Proveedor::find($this->proveedor_id);
            if ($proveedor) {
                $documentoProveedor = $proveedor->traer_documento_valido($this->tipo_documento_proveedor_id, $this->fecha_cierre);
            }
        }

        // Preparar datos del tipo de documento del proveedor con el ID del documento
        $tipoDocumentoProveedor = null;
        if ($this->tipo_documento_proveedor_id && $this->tipo_documento_proveedor) {
            $fechaVencimiento = null;
            
            // Si el tipo de documento requiere vencimiento y existe un documento del proveedor
            if ($this->tipo_documento_proveedor->vencimiento && $documentoProveedor && $documentoProveedor->vencimiento) {
                $fechaVencimiento = $documentoProveedor->vencimiento->format('Y-m-d');
            }
            
            $tipoDocumentoProveedor = [
                'id' => $documentoProveedor ? $documentoProveedor->id : null,
                'nombre' => $this->tipo_documento_proveedor->nombre,
                'codigo' => $this->tipo_documento_proveedor->codigo,
                'vencimiento' => $this->tipo_documento_proveedor->vencimiento,
                'fecha_vencimiento' => $fechaVencimiento,
            ];
        }

        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'de_concurso' => $this->de_concurso,
            'obligatorio' => $this->obligatorio,
            'tipo_documento_proveedor_id' => $this->tipo_documento_proveedor_id,
            'tipo_documento_proveedor' => $tipoDocumentoProveedor,
            'documentos_oferta' => $documentosOferta,
            'proveedor_id' => $this->proveedor_id,
            'concurso_id' => $this->concurso_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
} 