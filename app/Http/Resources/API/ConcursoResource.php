<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class ConcursoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'numero' => $this->numero,
            'descripcion' => $this->descripcion,
            'fecha_inicio' => $this->fecha_inicio?->format('Y-m-d H:i:s'),
            'fecha_cierre' => $this->fecha_cierre?->format('Y-m-d H:i:s'),
            'numero_legajo' => $this->numero_legajo,
            'permite_carga' => $this->permite_carga,
            'legajo' => $this->legajo,
            'estado' => [
                'id' => $this->estado?->id,
                'nombre' => $this->estado?->nombre,
                'estado_actual' => $this->estado_actual
            ],
            'subrubro' => [
                'id' => $this->subrubro?->id,
                'nombre' => $this->subrubro?->nombre,
                'rubro' => [
                    'id' => $this->subrubro?->rubro?->id,
                    'nombre' => $this->subrubro?->rubro?->nombre
                ]
            ],
            'contactos' => ContactoConcursoResource::collection($this->whenLoaded('contactos')),
            'sedes' => SedeResource::collection($this->whenLoaded('sedes')),
            'prorrogas' => ProrrogaResource::collection($this->whenLoaded('prorrogas')),
            'documentos' => ConcursoDocumentoResource::collection($this->whenLoaded('documentos')),
            'documentos_requeridos' => DocumentoTipoResource::collection($this->whenLoaded('documentos_requeridos')),
            'invitacion' => new InvitacionResource($this->whenLoaded('invitaciones')->first()),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
} 