<?php

namespace App\Models\Capacitaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $connection = 'capacitaciones';

    protected $guarded = [];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class);
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class);
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class);
    }

    public function correctas()
    {
        $cantidad = 0;
        $respuestas = count($this->respuestas);
        foreach($this->respuestas as $respuesta) {
            $cantidad += $respuesta->opcion->correcta;
        }
        return $respuestas ? round(($cantidad*100)/$respuestas, 2) : $respuestas;
    }
}
