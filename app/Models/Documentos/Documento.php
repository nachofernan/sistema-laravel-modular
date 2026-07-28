<?php

namespace App\Models\Documentos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $connection = 'documentos';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'observaciones',
        'archivo',
        'extension',
        'mimeType',
        'version',
        'orden',
        'visible',
        'publico',
        'user_id',
        'categoria_id',
        'archivo_uploaded_at',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'publico' => 'boolean',
        'orden' => 'integer',
        'archivo_uploaded_at' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function descargas()
    {
        return $this->hasMany(Descarga::class);
    }

    /**
     * Un documento se descarga sin login sólo si él y toda su rama de categorías
     * son públicos. Lo consulta el portal público antes de servir el archivo.
     */
    public function esPublico(): bool
    {
        return $this->publico && (bool) $this->categoria?->esPublica();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('documentos');
    }
}
