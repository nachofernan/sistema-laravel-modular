<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registrador extends Model
{
    use HasFactory;

    protected $connection = 'despacho';
    protected $table = 'registradores';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',           // principal | respaldo | control | auxiliar
        'tipo_dato',      // pulsos | potencia
        'columna_datos',
        'factor_conversion',
        'activo',
        'notas',
    ];

    protected $casts = [
        'activo'            => 'boolean',
        'columna_datos'     => 'integer',
        'factor_conversion' => 'decimal:6',
    ];

    public function maquinas(): BelongsToMany
    {
        return $this->belongsToMany(Maquina::class, 'maquina_registrador');
    }

    public function lecturas(): HasMany
    {
        return $this->hasMany(Lectura::class);
    }
}