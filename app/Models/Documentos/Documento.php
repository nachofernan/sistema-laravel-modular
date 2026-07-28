<?php

namespace App\Models\Documentos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
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
        'version' => 'integer',
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

    public function versiones()
    {
        return $this->hasMany(DocumentoVersion::class)->orderByDesc('version');
    }

    /**
     * Reemplaza el archivo vigente y archiva el anterior: el que estaba pasa a la
     * colección `historial` y queda registrado en `documento_versiones` con el
     * número de versión que tenía. El documento avanza a la versión siguiente.
     *
     * Lo llama el alta y la edición del panel, que son los dos únicos lugares por
     * donde entra un archivo al módulo.
     */
    public function reemplazarArchivo(UploadedFile $archivo, ?string $notas = null, ?int $usuarioId = null): void
    {
        $anterior = $this->getFirstMedia('archivos');

        if ($anterior) {
            $archivado = $anterior->move($this, 'historial');

            $this->versiones()->create([
                'version' => $this->version,
                'media_id' => $archivado->id,
                'archivo' => $archivado->file_name,
                'notas' => $notas,
                'subido_por' => $usuarioId,
            ]);

            $this->version = $this->version + 1;
        }

        $media = $this->addMedia($archivo)
            ->usingFileName($archivo->getClientOriginalName())
            ->toMediaCollection('archivos');

        $this->archivo = $media->file_name;
        $this->mimeType = $media->mime_type;
        $this->extension = $media->getExtensionAttribute();
        $this->archivo_uploaded_at = now();
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
        // Archivos de versiones anteriores. Nunca se sirve desde el portal público.
        $this->addMediaCollection('historial')->useDisk('documentos');
    }
}
