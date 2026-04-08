<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Maquina extends Model
{
    protected $connection = 'despacho';
    protected $table = 'maquinas';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function registradores(): BelongsToMany
    {
        return $this->belongsToMany(Registrador::class, 'maquina_registrador');
    }

    public function lecturas(): HasManyThrough
    {
        return $this->hasManyThrough(Lectura::class, Registrador::class);
    }
}