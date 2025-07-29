<?php

namespace App\Models\Proveedores;

use App\Models\Concursos\Concurso;
use App\Models\Concursos\Invitacion;
use App\Models\Shared\Pivots\ConcursoProveedor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $connection = 'proveedores';

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function proveedor_externo()
    {
        return $this->hasOne(ProveedorExterno::class, 'username', 'cuit');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }

    public function actualizador()
    {
        return $this->belongsTo(User::class, 'user_id_updated');
    }

    public function contactos()
    {
        return $this->hasMany(Contacto::class);
    }

    public function datos_bancarios()
    {
        return $this->hasMany(Bancario::class)->orderBy('created_at', 'desc');
    }

    public function direcciones()
    {
        return $this->hasMany(Direccion::class);
    }

    /* public function documentos()
    {
        //return $this->hasMany(Provdocumento::class);
        // supliers->hasMany('documents')->select('documents.*')->join('document_types', 'document_types.id', '=', 'documents.document_type_id')->orderBy('document_types.name')->orderBy('documents.upload_date')
        return $this->hasMany(Documento::class)->select('documentos.*')->join('documento_tipos', 'documento_tipos.id', '=', 'documentos.documento_tipo_id')->orderBy('documento_tipos.codigo')->orderBy('documentos.created_at', 'desc');
    } */

    public function documentos($soloValidados = true)
    {
        if($soloValidados) {
            return $this->morphMany(Documento::class, 'documentable')->whereHas('validacion', function ($query) {
                $query->where('validado', true);
            })->orderBy('documento_tipo_id', 'asc')->orderBy('created_at', 'desc');
        } else {
            return $this->morphMany(Documento::class, 'documentable')->orderBy('documento_tipo_id', 'asc')->orderBy('created_at', 'desc');
        }
    }

    public function ultimo_documento(DocumentoTipo $documento_tipo, $soloValidados = true)
    {
        if($soloValidados) {
            return Documento::where('documento_tipo_id', $documento_tipo->id)
                            ->where('documentable_id', $this->id)
                            ->where('documentable_type', 'App\Models\Proveedores\Proveedor')
                            ->whereHas('validacion', function ($query) {
                                $query->where('validado', true);
                            })
                            ->orderBy('created_at', 'desc')
                            ->first();
        } else {
            return Documento::where('documento_tipo_id', $documento_tipo->id)->where('documentable_id', $this->id)->where('documentable_type', 'App\Models\Proveedores\Proveedor')->orderBy('created_at', 'desc')->first();
        }
    }

    public function codigos_documentos($soloValidados = true)
    {
        if($soloValidados) {
            return $this->morphMany(Documento::class, 'documentable')->select('documento_tipos.codigo', 'documento_tipos.nombre')->join('documento_tipos', 'documento_tipos.id', '=', 'documentos.documento_tipo_id')->whereHas('validacion', function ($query) {
                $query->where('validado', true);
            })->orderBy('documento_tipos.codigo')->distinct();
        } else {
            return $this->morphMany(Documento::class, 'documentable')->select('documento_tipos.codigo', 'documento_tipos.nombre')->join('documento_tipos', 'documento_tipos.id', '=', 'documentos.documento_tipo_id')->orderBy('documento_tipos.codigo')->distinct();
        }
    }

    public function subrubros()
    {
        return $this->belongsToMany(Subrubro::class)->orderBy('rubro_id');
    }

    /* public function falta_a_vencimiento()
    {
        $ahora = \Carbon\Carbon::now();
        $fecha = 1000;
        $td = 0;
        foreach ($this->documentos as $documento) {
            if($documento->documentoTipo->vencimiento && $documento->vencimiento && $td != $documento->documentoTipo->id) {
                $td = $documento->documentoTipo->id;
                if($documento->vencimiento) {
                    //$dif = $ahora->diffInDays(\Carbon\Carbon::create($documento->vencimiento), false);
					$dif = $ahora->diff(\Carbon\Carbon::create($documento->vencimiento))->days;
                    if($fecha > $dif) {
                        $fecha = $dif;
                    }
                }
            }
        }
        return $fecha;
    } */

	public function falta_a_vencimiento()
    {
        $now = \Carbon\Carbon::now()->addYear();
        $fecha = 1000;
        $td = 0;
        foreach ($this->documentos as $documento) {
            if($td != $documento->documentoTipo->id) {
                $td = $documento->documentoTipo->id;
                if($documento->documentoTipo->vencimiento && $documento->vencimiento) {
                    if($documento->vencimiento && $documento->vencimiento < $now) {
                        $vencimiento = \Carbon\Carbon::create($documento->vencimiento);
                        if($vencimiento->isPast()) {
                            return -1;
                        } else {
                            $vencimiento->subDays(30);
                            if($vencimiento->isPast()) {
                                return 15;
                            }
                        }
                    }
                }
            }
        }
        return $fecha;
    } 

    /* public function concursos()
    {
        return $this->belongsToMany(Concurso::class, 'concursos.concurso_proveedor', 'proveedor_id', 'concurso_id')
            ->using(ConcursoProveedor::class)
            ->withPivot(['id'])
            ->withTimestamps();
    } */

    public function invitaciones()
    {
        return $this->hasMany(Invitacion::class);
    }

    /**
     * Trae el documento válido (validado, no vencido, cargado antes de una fecha límite) para un tipo dado.
     */
    public function traer_documento_valido($documento_tipo_id, $fecha_limite = null)
    {
        $query = Documento::where('documento_tipo_id', $documento_tipo_id)
            ->where('documentable_id', $this->id)
            ->where('documentable_type', 'App\\Models\\Proveedores\\Proveedor')
            ->whereHas('validacion', function ($q) {
                $q->where('validado', true);
            });

        if ($fecha_limite) {
            $query->where('created_at', '<=', $fecha_limite);
        }

        $query->where(function($q) use ($fecha_limite) {
            $q->whereNull('vencimiento')
              ->orWhere('vencimiento', '>=', $fecha_limite ?? now());
        });

        return $query->orderBy('created_at', 'desc')->first();
    }

    /**
     * Devuelve todos los documentos válidos (validación positiva, no vencidos a una fecha) para un tipo.
     */
    public function documentos_validos($documento_tipo_id, $fecha_limite = null)
    {
        $query = Documento::where('documento_tipo_id', $documento_tipo_id)
            ->where('documentable_id', $this->id)
            ->where('documentable_type', 'App\\Models\\Proveedores\\Proveedor')
            ->whereHas('validacion', function ($q) {
                $q->where('validado', true);
            });
        if ($fecha_limite) {
            $query->where('created_at', '<=', $fecha_limite);
        }
        $query->where(function($q) use ($fecha_limite) {
            $q->whereNull('vencimiento')
              ->orWhere('vencimiento', '>=', $fecha_limite ?? now());
        });
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Trae el documento más reciente validado, sin filtrar por fecha ni vencimiento (compatibilidad).
     */
    public function traer_documento($documento_tipo_id, $soloValidados = true)
    {
        if($soloValidados) {
            return Documento::where('documento_tipo_id', $documento_tipo_id)
                            ->where('documentable_id', $this->id)
                            ->where('documentable_type', 'App\\Models\\Proveedores\\Proveedor')
                            ->whereHas('validacion', function ($query) {
                                $query->where('validado', true);
                            })
                            ->orderBy('created_at', 'desc')
                            ->first();
        } else {
            return Documento::where('documento_tipo_id', $documento_tipo_id)
                ->where('documentable_id', $this->id)
                ->where('documentable_type', 'App\\Models\\Proveedores\\Proveedor')
                ->orderBy('created_at', 'desc')
                ->first();
        }
    }

    public function concursos()
    {
        return $this->belongsToMany(Concurso::class, 'concursos.concurso_proveedor', 'proveedor_id', 'concurso_id')
            ->using(ConcursoProveedor::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function apoderados($soloValidados = true)
    {
        if($soloValidados) {
            return $this->hasMany(Apoderado::class)->whereHas('documentos', function ($query) {
                $query->whereHas('validacion', function ($q) {
                    $q->where('validado', true);
                });
            });
        } else {
            return $this->hasMany(Apoderado::class);
        }
    }

    /**
     * Obtiene solo los últimos documentos validados por tipo de documento.
     * Retorna un documento por cada tipo de documento que tenga al menos un documento validado.
     */
    public function ultimosDocumentosValidados()
    {
        return Documento::select('documentos.*')
            ->join('validacions', 'documentos.id', '=', 'validacions.documento_id')
            ->where('documentable_id', $this->id)
            ->where('documentable_type', 'App\Models\Proveedores\Proveedor')
            ->where('validacions.validado', true)
            ->whereNotNull('documento_tipo_id')
            ->orderBy('documento_tipo_id')
            ->orderBy('documentos.created_at', 'desc')
            ->get()
            ->groupBy('documento_tipo_id')
            ->map(function ($documentos) {
                return $documentos->first();
            })
            ->values();
    }
}
