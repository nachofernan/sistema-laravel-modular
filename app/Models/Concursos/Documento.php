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
}
