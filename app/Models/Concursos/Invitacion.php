<?php

namespace App\Models\Concursos;

use App\Models\Proveedores\Proveedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo

    protected $connection = 'concursos'; // Setea la conexión a la base de datos

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function documentos()
    {
        return $this->hasMany(OfertaDocumento::class);
    }

    public function documentos_con_tipo_id($documentoTipoId)
    {
        return $this->hasMany(OfertaDocumento::class)->where('documento_tipo_id', $documentoTipoId)->get();
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
