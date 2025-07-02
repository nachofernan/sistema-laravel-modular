<?php

namespace App\Models\Capacitaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $connection = 'capacitaciones';

    protected $guarded = [];
    
    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class);
    }

    public function respondida_por($user_id)
    {
        return Respuesta::where('user_id', $user_id)->where('pregunta_id', $this->preguntas()->first()->id)->get();
    }

    public function estado()
    {
        $retorno = array([
            'id' => '',
            'nombre' => '',
            'verbo' => '',
        ]);
        if($this->estado == 0) {
            $retorno['id'] = 0;
            $retorno['nombre'] = 'inactiva';
            $retorno['verbo'] = 'desactivar';
        }
        if($this->estado == 1) {
            $retorno['id'] = 1;
            $retorno['nombre'] = 'activa';
            $retorno['verbo'] = 'activar';
        }
        if($this->estado == 2) {
            $retorno['id'] = 2;
            $retorno['nombre'] = 'finalizada';
            $retorno['verbo'] = 'finalizar';
        }
        return $retorno;
    }
}
