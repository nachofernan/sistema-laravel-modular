<?php

namespace App\Models\Concursos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $connection = 'concursos';

    protected $guarded = false;

    protected $casts = [
        'encriptado' => 'boolean',
    ];

    public function concurso() {
        return $this->belongsTo(Concurso::class);
    }

    public function documentoTipo() {
        return $this->belongsTo(DocumentoTipo::class);
    }

    public function invitacion() {
        return $this->belongsTo(Invitacion::class);
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
     * Retorna true si el documento es válido para la oferta (validado, no vencido a la fecha de cierre).
     */
    public function esValidoParaOferta($fecha_cierre)
    {
        $validado = $this->validacion && $this->validacion->validado;
        $noVencido = !$this->vencimiento || $this->vencimiento->gte($fecha_cierre);
        $bloqueado = $this->created_at <= $fecha_cierre;
        return $validado && $noVencido && $bloqueado;
    }
}
