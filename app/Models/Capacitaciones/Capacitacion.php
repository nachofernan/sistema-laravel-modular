<?php

namespace App\Models\Capacitaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacitacion extends Model
{
    use HasFactory;

    protected $connection = 'capacitaciones';

    protected $guarded = [];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
    ];

    public function invitaciones()
    {
        return $this->hasMany(Invitacion::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function encuestas()
    {
        return $this->hasMany(Encuesta::class);
    }
}
