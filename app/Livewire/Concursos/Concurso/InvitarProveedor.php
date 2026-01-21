<?php

namespace App\Livewire\Concursos\Concurso;

use App\Helpers\EmailHelper;
use App\Mail\Concursos\NuevaInvitacion;
use App\Models\Concursos\Concurso;
use App\Models\Concursos\Invitacion;
use App\Models\Proveedores\Proveedor;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InvitarProveedor extends Component
{
    public $open = false;

    public $concurso;

    public $subrubro;
    public $search = '';
    public $proveedoresRecomendados = [];
    public $proveedoresBuscados = [];


    public function mount(Concurso $concurso)
    {
        $this->concurso = $concurso;
        $this->subrubro = $concurso->subrubro;
        $this->cargarProveedoresRecomendados();
    }

    public function cargarProveedoresRecomendados()
    {
        if($this->subrubro) {
            $this->proveedoresRecomendados = Proveedor::whereHas('subrubros', function ($query) {
                $query->where('subrubro_id', $this->subrubro->id);
            })
            ->whereNotIn('id', collect($this->concurso->invitaciones)->pluck('proveedor_id'))
            ->get();
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 3) {
            $this->proveedoresBuscados = Proveedor::where(function ($query) {
                $query->where('cuit', 'like', '%' . $this->search . '%')
                    ->orWhere('razonsocial', 'like', '%' . $this->search . '%');
            })
            ->whereNotIn('id', collect($this->concurso->invitaciones)->pluck('proveedor_id'))
            ->get();
        } else {
            $this->proveedoresBuscados = [];
        }
    }

    public function agregarInvitacion(Proveedor $proveedor)
    {
        $invitacion = Invitacion::create([
            'concurso_id' => $this->concurso->id,
            'proveedor_id' => $proveedor->id
        ]);
        if($this->concurso->estado->id == 2) {
            // Crear el modelo del mail y el mail
            /* if(str_ends_with($invitacion->proveedor->correo, '@buenosairesenergia.com.ar')) {
                Mail::to([$invitacion->proveedor->correo])->send(new NuevaInvitacion($invitacion));
            } */
            $correos[] = $invitacion->proveedor->correo;
            foreach($proveedor->contactos as $contacto) {
                $correos[] = $contacto->correo;
            }
            $correos = array_unique($correos);
            EmailHelper::notificarAperturaConcurso($this->concurso, $correos);
            EmailHelper::programarEmailsAutomaticosConcurso($this->concurso, $correos);
        }
        $this->cargarProveedoresRecomendados();
        $this->updatedSearch();
    }

    public function quitarInvitacion(Invitacion $invitacion)
    {
        $invitacion->delete();
        $this->cargarProveedoresRecomendados();
    }

    public function reInvitar(Invitacion $invitacion)
    {
        $invitacion->intencion = 0;
        $invitacion->save();
        // Crear el modelo del mail y el mail
        $correos = [$invitacion->proveedor->correo];
        foreach($invitacion->proveedor->contactos as $contacto) {
            $correos[] = $contacto->correo;
        }
        $correos = array_unique($correos);
        foreach($correos as $correo) {
            if(app()->environment('production') || str_ends_with($correo, '@buenosairesenergia.com.ar')) {
                Mail::to($correo)->send(new NuevaInvitacion($invitacion));
            }
        }
        //Mail::to(['ifernandez@ccasa.com.ar'])->send(new NuevaInvitacion($invitacion));
        $this->cargarProveedoresRecomendados();
    }

    public function render()
    {
        return view('livewire.concursos.concurso.invitar-proveedor');
    }
}
