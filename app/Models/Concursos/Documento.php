<?php

namespace App\Models\Concursos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $connection = 'concursos';

    protected $guarded = false;

    public function concurso() {
        return $this->belongsTo(Concurso::class);
    }

    public function documentoTipo() {
        return $this->belongsTo(DocumentoTipo::class);
    }

    public function invitacion() {
        return $this->belongsTo(Invitacion::class);
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
