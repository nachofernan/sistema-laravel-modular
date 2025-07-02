<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\Contacto;
use Livewire\Component;

class AdministrarContactos extends Component
{
    public $concurso;
    
    public $open_edit = false;
    public $encargado_edit = null;
    public $nombre_edit = '';
    public $correo_edit = '';
    public $telefono_edit = '';
    public $tipo_edit = 'administrativo';
    
    public $open_nuevo = false;
    public $nombre = '';
    public $correo = '';
    public $telefono = '';
    public $tipo = 'administrativo';

    public function mount($concurso)
    {
        $this->concurso = $concurso;
    }

    public function guardar()
    {
        $encargado = new Contacto();
        $encargado->nombre = $this->nombre;
        $encargado->correo = $this->correo;
        $encargado->telefono = $this->telefono;
        $encargado->tipo = $this->tipo;
        $encargado->concurso_id = $this->concurso->id;
        $encargado->save();

        $this->nombre = '';
        $this->correo = '';
        $this->telefono = '';
        $this->tipo = 'administrativo';
        $this->open_nuevo = false;
    }

    public function abrirYeditar($encargado_id)
    {
        $this->encargado_edit = Contacto::find($encargado_id);
        $this->nombre_edit = $this->encargado_edit->nombre;
        $this->correo_edit = $this->encargado_edit->correo;
        $this->telefono_edit = $this->encargado_edit->telefono;
        $this->tipo_edit = $this->encargado_edit->tipo;

        $this->open_edit = true;
    }

    public function actualizar()
    {
        $this->encargado_edit->nombre = $this->nombre_edit;
        $this->encargado_edit->correo = $this->correo_edit;
        $this->encargado_edit->telefono = $this->telefono_edit;
        $this->encargado_edit->tipo = $this->tipo_edit;
        $this->encargado_edit->save();
        
        $this->encargado_edit = null;
        $this->nombre_edit = '';
        $this->correo_edit = '';
        $this->telefono_edit = '';
        $this->tipo_edit = 'administrativo';

        $this->open_edit = false;
    }


    public function render()
    {
        return view('livewire.concursos.concurso.show.administrar-contactos');
    }
}
