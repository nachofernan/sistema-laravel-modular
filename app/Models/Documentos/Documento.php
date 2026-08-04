<?php

namespace App\Models\Documentos;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
     * Busca por nombre, código y descripción. El término va agrupado en su propio
     * where para no romper los filtros que traiga la query de afuera.
     */
    public function scopeBuscar(Builder $query, string $termino): Builder
    {
        return $query->where(fn (Builder $q) => $q
            ->where('nombre', 'like', "%{$termino}%")
            ->orWhere('codigo', 'like', "%{$termino}%")
            ->orWhere('descripcion', 'like', "%{$termino}%"));
    }

    /**
     * Reemplaza el archivo vigente y archiva el anterior: el que estaba pasa a la
     * colección `historial` y queda registrado en `documento_versiones` con el
     * número de versión que tenía.
     *
     * El número de la versión nueva lo decide quien sube el archivo (`$version`):
     * un documento puede venir de Control de Gestión ya en la v4 sin haber pasado
     * por las tres anteriores acá. Sin número, se sigue la numeración del sistema.
     *
     * Lo llaman el alta y la edición del panel y el modal de nueva versión, que son
     * los únicos lugares por donde entra un archivo al módulo.
     *
     * Cubierto por `el numero de version lo decide quien sube el archivo`.
     */
    public function reemplazarArchivo(UploadedFile $archivo, ?string $notas = null, ?int $usuarioId = null, ?int $version = null): void
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

            $this->version = $version ?? $this->version + 1;
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
