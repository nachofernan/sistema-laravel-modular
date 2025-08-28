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
     * Obtiene todos los services asociados a este vehículo
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Obtiene el nombre completo del vehículo (marca + modelo)
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->marca} {$this->modelo}";
    }
    
    public function getNecesitaServiceAttribute(): bool
    {
        $resto = $this->kilometraje % 10000;
        if ($resto >= 9000 || $resto <= 3000) { //Estamos en la ventana del service
            $ultimoService = $this->services()->orderByDesc('fecha_service')->first();
            if(!$ultimoService || ($this->kilometraje - $ultimoService->kilometros) > 6000) {
                return true; //Necesita service
            }
        }
        return false;
    }
}
