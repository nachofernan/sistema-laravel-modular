<?php

namespace App\Models\Automotores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Vehiculo extends Model
{
    use HasFactory;

    protected $connection = 'automotores';

    protected $table = 'vehiculos';

    protected $fillable = [
        'marca',
        'modelo',
        'patente',
        'kilometraje',
    ];

    /**
     * Obtiene todas las COPRES asociadas a este vehículo
     */
    public function copres(): HasMany
    {
        return $this->hasMany(Copres::class);
    }

    /**
     * Obtiene el nombre completo del vehículo (marca + modelo)
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->marca} {$this->modelo}";
    }
}
