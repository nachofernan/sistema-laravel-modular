<?php

namespace App\Models\Concursos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $connection = 'concursos';

    protected $guarded = false;

    protected $casts = [
        'encriptado' => 'boolean',
    ];

    public function concurso() {
        return $this->belongsTo(Concurso::class);
    }

    public function documentoTipo() {
        return $this->belongsTo(DocumentoTipo::class);
    }

    public function invitacion() {
        return $this->belongsTo(Invitacion::class);
    }

    // (Opcional) Puedes definir la colección por defecto si quieres validaciones o conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('concursos');
    }
}
