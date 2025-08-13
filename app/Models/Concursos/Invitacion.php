<?php

namespace App\Models\Concursos;

use App\Models\Proveedores\Proveedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Invitacion extends Model
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo
    protected $connection = 'concursos'; // Setea la conexión a la base de datos

    public function concurso() {
        return $this->belongsTo(Concurso::class);
    }

    public function proveedor() {
        return $this->belongsTo(Proveedor::class);
    }

    public function documentos() {
        return $this->hasMany(OfertaDocumento::class);
    }
    
    public function documentos_con_tipo_id($documentoTipoId)
    {
        return $this->hasMany(OfertaDocumento::class)->where('documento_tipo_id', $documentoTipoId)->get();
    }

    /**
     * Método de debugging para documentos con tipo_id
     */
    public function debug_documentos_con_tipo_id($documentoTipoId)
    {
        $query = $this->hasMany(OfertaDocumento::class)->where('documento_tipo_id', $documentoTipoId);
        
        // Log de la consulta SQL
        Log::info('SQL Query: ' . $query->toSql());
        Log::info('Bindings: ' . json_encode($query->getBindings()));
        
        $result = $query->get();
        
        Log::info('Result count: ' . $result->count());
        if($result->count() > 0) {
            Log::info('First document: ' . json_encode($result->first()->toArray()));
        }
        
        return $result;
    }

    /**
     * Método para verificar directamente en la base de datos
     */
    public function verificar_documentos_directo($documentoTipoId)
    {
        // Consulta directa a la base de datos
        $documentos = DB::connection('concursos')
            ->table('oferta_documentos')
            ->where('invitacion_id', $this->id)
            ->where('documento_tipo_id', $documentoTipoId)
            ->get();
        
        Log::info('Consulta directa - Documentos encontrados: ' . $documentos->count());
        if($documentos->count() > 0) {
            Log::info('Primer documento directo: ' . json_encode($documentos->first()));
        }
        
        return $documentos;
    }

    /**
     * Método para verificar todos los documentos de la invitación
     */
    public function verificar_todos_documentos()
    {
        // Consulta directa a la base de datos
        $documentos = DB::connection('concursos')
            ->table('oferta_documentos')
            ->where('invitacion_id', $this->id)
            ->get();
        
        Log::info('Todos los documentos de la invitación: ' . $documentos->count());
        foreach($documentos as $doc) {
            Log::info('Documento ID: ' . $doc->id . ', Tipo: ' . $doc->documento_tipo_id . ', Archivo: ' . $doc->archivo);
        }
        
        return $documentos;
    }

    public function documentos_sin_tipo_id()
    {
        return $this->hasMany(OfertaDocumento::class)
                    ->whereNull('documento_tipo_id')
                    ->where('created_at', '<=', $this->concurso->fecha_cierre)
                    ->get();
    }

    public function documentos_post_concurso()
    {
        return $this->hasMany(OfertaDocumento::class)
                    ->whereNull('documento_tipo_id')
                    ->where('created_at', '>', $this->concurso->fecha_cierre)
                    ->get();
    }

    public function documentos_baesa()
    {
        return $this->hasMany(OfertaDocumento::class)
            ->whereNotNull('user_id_created')
            ->get();
    }

    /**
     * Obtiene todos los documentos de oferta requeridos (con documento_tipo_id).
     */
    public function documentosRequeridos()
    {
        return $this->hasMany(OfertaDocumento::class)
                    ->whereNotNull('documento_tipo_id');
    }

    /**
     * Obtiene todos los documentos de oferta adicionales (sin documento_tipo_id).
     */
    public function documentosAdicionales()
    {
        return $this->hasMany(OfertaDocumento::class)
                    ->whereNull('documento_tipo_id');
    }

    /**
     * Obtiene documentos subidos por el proveedor.
     */
    public function documentosSubidosPorProveedor()
    {
        return $this->hasMany(OfertaDocumento::class)
                    ->whereNull('user_id_created');
    }

    /**
     * Obtiene documentos ingresados por usuarios de la empresa.
     */
    public function documentosIngresadosPorEmpresa()
    {
        return $this->hasMany(OfertaDocumento::class)
                    ->whereNotNull('user_id_created');
    }

    /**
     * Devuelve el documento de proveedor válido para un tipo y la fecha de cierre del concurso.
     */
    public function documento_proveedor_valido($documento_tipo_id)
    {
        $fecha_cierre = $this->concurso->fecha_cierre;
        return $this->proveedor->traer_documento_valido($documento_tipo_id, $fecha_cierre);
    }
}
