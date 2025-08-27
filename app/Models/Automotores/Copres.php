<?php

namespace App\Models\Automotores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Copres extends Model
{
    use HasFactory;

    protected $connection = 'automotores';

    protected $table = 'copres';

    protected $fillable = [
        'fecha',
        'numero_ticket_factura',
        'cuit',
        'vehiculo_id',
        'litros',
        'precio_x_litro',
        'importe_final',
        'km_vehiculo',
        'kz',
        'salida',
        'reentrada',
        'user_id_creator',
        'user_id_chofer',
    ];

    protected $casts = [
        'fecha' => 'date',
        'litros' => 'decimal:2',
        'precio_x_litro' => 'decimal:2',
        'importe_final' => 'decimal:2',
        'km_vehiculo' => 'integer',
        'kz' => 'integer',
        'salida' => 'date',
        'reentrada' => 'date',
    ];

    /**
     * Obtiene el vehículo asociado a esta COPRES
     */
    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Obtiene el usuario que creó esta COPRES
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_creator');
    }

    /**
     * Obtiene el chofer asociado a esta COPRES
     */
    public function chofer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_chofer');
    }
}
