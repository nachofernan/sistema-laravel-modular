<?php

namespace App\Models\Documentos;

use App\Models\User;
use App\Models\Usuarios\Sede;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $connection = 'documentos';

    protected $guarded = [];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function descargas()
    {
        return $this->hasMany(Descarga::class);
    }

    // (Opcional) Puedes definir la colección por defecto si quieres validaciones o conversiones
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivos')->useDisk('documentos');
    }
}
