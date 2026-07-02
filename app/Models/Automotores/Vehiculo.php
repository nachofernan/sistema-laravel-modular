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
    
    // Cada cuántos km se hace el service
    const KM_INTERVALO_SERVICE = 10000;
    // Se muestra alerta cuando el odómetro está dentro de esta ventana antes/después del múltiplo
    const KM_VENTANA_ALERTA_ANTES = 1000;  // últimos 1000 km antes del service
    const KM_VENTANA_ALERTA_DESPUES = 3000; // hasta 3000 km después del múltiplo
    // Si el service anterior está a más de este valor, se ignora y se alerta igual
    const KM_MAXIMO_DESDE_ULTIMO_SERVICE = 6000;

    public function getNecesitaServiceAttribute(): bool
    {
        $resto = $this->kilometraje % self::KM_INTERVALO_SERVICE;
        $enVentana = $resto >= (self::KM_INTERVALO_SERVICE - self::KM_VENTANA_ALERTA_ANTES)
                  || $resto <= self::KM_VENTANA_ALERTA_DESPUES;

        if ($enVentana) {
            $ultimoService = $this->services()->orderByDesc('fecha_service')->first();
            if (!$ultimoService || ($this->kilometraje - $ultimoService->kilometros) > self::KM_MAXIMO_DESDE_ULTIMO_SERVICE) {
                return true;
            }
        }
        return false;
    }
}
