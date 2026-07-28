<?php

namespace App\Models\Documentos;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $connection = 'documentos';

    protected $fillable = [
        'nombre',
        'categoria_padre_id',
        'publica',
        'orden',
    ];

    protected $casts = [
        'publica' => 'boolean',
        'orden' => 'integer',
    ];

    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'categoria_padre_id');
    }

    public function hijos()
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id')->orderBy('orden');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    /**
     * Documentos que se muestran en el portal público, ya ordenados. Es una relación
     * y no un accessor para poder cargarla con eager loading desde la vista pública.
     */
    public function documentosPublicos()
    {
        return $this->documentos()->where('publico', true)->orderBy('orden')->orderBy('nombre');
    }

    /** Categorías que arman el menú público: las raíces marcadas como públicas. */
    public function scopeRaicesPublicas(Builder $query): Builder
    {
        return $query->whereNull('categoria_padre_id')->where('publica', true)->orderBy('orden');
    }

    /** Sólo las subcategorías admiten documentos; las raíces son contenedores. */
    public function scopeSubcategorias(Builder $query): Builder
    {
        return $query->whereNotNull('categoria_padre_id')->orderBy('orden');
    }

    /**
     * Una subcategoría no se muestra sin login si su categoría padre no es pública,
     * aunque ella misma lo sea.
     */
    public function esPublica(): bool
    {
        return $this->publica && (! $this->categoria_padre_id || (bool) $this->padre?->publica);
    }
}
