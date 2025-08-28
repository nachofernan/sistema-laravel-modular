<?php

namespace App\Models\Automotores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $connection = 'automotores';

    protected $table = 'services';

    protected $fillable = [
        'vehiculo_id',
        'kilometros',
        'fecha_service',
    ];

    protected $casts = [
        'fecha_service' => 'date',
    ];

    /**
     * Obtiene el vehículo asociado a este service
     */
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Obtiene los kilómetros formateados
     */
    public function getKilometrosFormateadoAttribute(): string
    {
        return number_format($this->kilometros) . ' km';
    }
}
