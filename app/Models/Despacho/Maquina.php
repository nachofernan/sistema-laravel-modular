<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Maquina extends Model
{
    use HasFactory;

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
}