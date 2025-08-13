<?php

namespace App\Models\Concursos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ConcursoDocumento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $connection = 'concursos';

    protected $guarded = false;

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function documentoTipo()
    {
        return $this->belongsTo(DocumentoTipo::class);
    }

    // (Opcional) Puedes definir la colección por defecto si quieres validaciones o conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('concursos');
    }

    /**
     * Retorna true si el documento está bloqueado (cargado antes o en la fecha de cierre del concurso).
     */
    public function estaBloqueado($fecha_cierre)
    {
        return $this->created_at <= $fecha_cierre;
    }
} 