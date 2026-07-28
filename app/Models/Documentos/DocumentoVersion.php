<?php

namespace App\Models\Documentos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Una versión anterior del archivo de un documento. Se crea al reemplazar el
 * archivo: la fila describe lo que estaba antes, no lo que se sube.
 */
class DocumentoVersion extends Model
{
    use HasFactory;

    protected $connection = 'documentos';

    protected $table = 'documento_versiones';

    protected $fillable = [
        'documento_id',
        'version',
        'media_id',
        'archivo',
        'notas',
        'subido_por',
    ];

    protected $casts = [
        'version' => 'integer',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    /**
     * El archivo guardado en la colección `historial`. Devuelve null si el media
     * se perdió: la fila queda igual como registro de que la versión existió.
     */
    public function media(): ?Media
    {
        return $this->media_id ? Media::find($this->media_id) : null;
    }
}
