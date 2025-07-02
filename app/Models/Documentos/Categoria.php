<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $connection = 'documentos';
    
    protected $guarded = [];

    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'categoria_padre_id');
    }

    public function hijos()
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    // Definir el accesor para documentos visibles
    public function getDocumentosVisiblesAttribute()
    {
        return $this->documentos()->where('visible', true)->get();
    }
}
