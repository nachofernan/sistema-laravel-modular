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
    // Aviso de "próximo service" en los últimos 1000 km antes del intervalo
    const KM_VENTANA_ALERTA_ANTES = 1000;

    public function getKmDesdeUltimoServiceAttribute(): int
    {
        $ultimoService = $this->services()->orderByDesc('fecha_service')->first();
        return $this->kilometraje - ($ultimoService->kilometros ?? 0);
    }

    public function getNecesitaServiceAttribute(): bool
    {
        return $this->km_desde_ultimo_service >= self::KM_INTERVALO_SERVICE;
    }

    public function getProximoServiceAttribute(): bool
    {
        return $this->km_desde_ultimo_service >= (self::KM_INTERVALO_SERVICE - self::KM_VENTANA_ALERTA_ANTES)
            && $this->km_desde_ultimo_service < self::KM_INTERVALO_SERVICE;
    }
}
