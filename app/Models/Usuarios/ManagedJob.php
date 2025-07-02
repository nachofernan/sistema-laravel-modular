<?php

namespace App\Models\Usuarios;

use App\Services\EmailDispatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagedJob extends Model
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo
    protected $connection = ''; // Setea la conexión a la base de datos

    protected $casts = [
        'tags' => 'array',
        'metadata' => 'array',
        'scheduled_for' => 'datetime'
    ];
    
    // Relación polimórfica
    public function entity()
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id');
    }
    
    // Scopes útiles
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeByEntity($query, $type, $id)
    {
        return $query->where('entity_type', $type)->where('entity_id', $id);
    }
    
    public function scopeByJobType($query, $type)
    {
        return $query->where('job_type', $type);
    }
    
    // Métodos estáticos para facilidad de uso
    public static function cancelByEntity($entityType, $entityId, $jobType = null)
    {
        return EmailDispatcher::cancelarJobsPorEntidad($entityType, $entityId, $jobType);
    }

    // Método alternativo más agresivo
    public static function cancelByEntityAgressive($entityType, $entityId, $jobType = null)
    {
        return EmailDispatcher::cancelarJobsPorEntidadAgresivo($entityType, $entityId, $jobType);
    }
}