<?php

namespace App\Models\Concursos;

use App\Models\Proveedores\DocumentoTipo as ProveedoresDocumentoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentoTipo extends Model
{
    use HasFactory;

    protected $guarded = false; // Abre todas las columnas del modelo
    protected $connection = 'concursos'; // Setea la conexión a la base de datos

    public function documentos() {
        return $this->hasMany(Documento::class); // Relación de uno a muchos con la tabla documentos (legacy)
    }

    public function ofertaDocumentos() {
        return $this->hasMany(OfertaDocumento::class); // Relación con documentos de oferta
    }

    public function concursoDocumentos() {
        return $this->hasMany(ConcursoDocumento::class); // Relación con documentos de concurso
    }

    public function tipo_documento_proveedor() {
        return $this->belongsTo(ProveedoresDocumentoTipo::class, 'tipo_documento_proveedor_id');
    }
 
    public function concursos()
    {
        return $this->belongsToMany(Concurso::class, 'concurso_documento_tipo', 'documento_tipo_id', 'concurso_id');
    }
}
