<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lectura extends Model
{
    use HasFactory;

    protected $connection = 'despacho';
    protected $table = 'lecturas';

    protected $fillable = [
        'registrador_id',
        'fecha',
        'bloque_horario',
        'hora_desde',
        'hora_hasta',
        'valor_crudo',
        'valor_convertido',
    ];

    protected $casts = [
        'fecha'            => 'date',
        'bloque_horario'   => 'integer',
        'valor_crudo'      => 'decimal:4',
        'valor_convertido' => 'decimal:4',
    ];

    public function registrador(): BelongsTo
    {
        return $this->belongsTo(Registrador::class);
    }
}