<?php

namespace App\Models\Concursos;

use App\Models\User;
use App\Models\Proveedores\Documento as DocumentoProveedor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class OfertaDocumento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $connection = 'concursos';

    protected $guarded = false;

    protected $casts = [
        'encriptado' => 'boolean',
    ];

    public function invitacion()
    {
        return $this->belongsTo(Invitacion::class);
    }

    public function documentoTipo()
    {
        return $this->belongsTo(DocumentoTipo::class);
    }

    public function documentoProveedor()
    {
        return $this->belongsTo(DocumentoProveedor::class, 'documento_proveedor_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }

    // (Opcional) Puedes definir la colección por defecto si quieres validaciones o conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('concursos');
    }

    /**
     * Retorna true si el documento está bloqueado (cargado antes o en la fecha de cierre del concurso).
     */
    public function estaBloqueado($fecha_cierre)
    {
        return $this->created_at <= $fecha_cierre;
    }

    /**
     * Retorna true si el documento es válido para la oferta.
     * Para documentos de oferta, consideramos válido si:
     * - Fue cargado antes o en la fecha de cierre del concurso
     * - No tiene validación específica (los documentos de oferta se consideran válidos por defecto)
     */
    public function esValidoParaOferta($fecha_cierre)
    {
        // Para documentos de oferta, consideramos válido si fue cargado antes del cierre
        return $this->created_at <= $fecha_cierre;
    }

    /**
     * Retorna true si el documento es requerido (tiene documento_tipo_id).
     */
    public function esRequerido()
    {
        return !is_null($this->documento_tipo_id);
    }

    /**
     * Retorna true si el documento es adicional (no tiene documento_tipo_id).
     */
    public function esAdicional()
    {
        return is_null($this->documento_tipo_id);
    }

    /**
     * Retorna true si el documento fue subido por el proveedor.
     */
    public function fueSubidoPorProveedor()
    {
        return is_null($this->user_id_created);
    }

    /**
     * Retorna true si el documento fue ingresado por un usuario de la empresa.
     */
    public function fueIngresadoPorEmpresa()
    {
        return !is_null($this->user_id_created);
    }

    /**
     * Obtiene la URL del archivo usando Spatie Media Library
     */
    public function getFileUrlAttribute()
    {
        $media = $this->getFirstMedia('archivos');
        return $media ? $media->getUrl() : null;
    }

    /**
     * Obtiene el nombre del archivo usando Spatie Media Library
     */
    public function getFileNameAttribute()
    {
        $media = $this->getFirstMedia('archivos');
        return $media ? $media->file_name : $this->archivo;
    }
} 