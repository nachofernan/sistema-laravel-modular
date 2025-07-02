<?php

namespace App\Models\Capacitaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $connection = 'capacitaciones';

    protected $guarded = [];

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacion::class);
    }
}
