<?php

namespace App\Models\Capacitaciones;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    protected $connection = 'capacitaciones';
    
    protected $guarded = [];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class);
    }

    public function opcion()
    {
        return $this->belongsTo(Opcion::class);
    }
}
