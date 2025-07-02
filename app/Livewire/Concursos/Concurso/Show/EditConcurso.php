<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\User;
use App\Models\Usuarios\Sede;
use Livewire\Component;
use Livewire\Attributes\On;

class EditConcurso extends Component
{
    public $open = false;
    public $concurso;
    public $nombre;
    public $descripcion;
    public $numero_legajo = '';
    public $legajo;
    public $fecha_inicio;
    public $fecha_cierre;
    public $user_id;
    public $users;
    public $sedes;
    public $sedeSeleccionadas = [];

    #[On('actualizarNumeroLegajo')]
    public function actualizarNumeroLegajo($numero_legajo)
    {
        $this->numero_legajo = $numero_legajo;
    }

    public function mount($concurso) {
        $this->concurso = $concurso;
        $this->nombre = $concurso->nombre;
        $this->descripcion = $concurso->descripcion;
        $this->numero_legajo = $concurso->numero_legajo;
        $this->legajo = $concurso->legajo;
        $this->user_id = $concurso->user_id;
        $this->fecha_inicio = $concurso->fecha_inicio->format('Y-m-d\TH:i');
        $this->fecha_cierre = $concurso->fecha_cierre->format('Y-m-d\TH:i');
        $this->users = User::orderBy('legajo')->get();
        $this->sedes = Sede::all();
        $this->sedeSeleccionadas = $concurso->sedes->pluck('id')->toArray();
    }

    public function guardar() {
        $this->concurso->nombre = $this->nombre;
        $this->concurso->descripcion = $this->descripcion;
        $this->concurso->numero_legajo = $this->numero_legajo;
        $this->concurso->legajo = $this->legajo;
        $this->concurso->fecha_inicio = $this->fecha_inicio;
        $this->concurso->fecha_cierre = $this->fecha_cierre;
        $this->concurso->user_id = $this->user_id ?: null;
        
        if($this->legajo){
            $explode = explode('?', $this->legajo);
            $this->concurso->legajo = $explode[0];
        }

        $this->concurso->save();

        if(!empty($this->sedeSeleccionadas))
            $this->concurso->sedes()->sync($this->sedeSeleccionadas);

        return redirect()->route('concursos.concursos.show', $this->concurso);
    }

    public function render()
    {
        return view('livewire.concursos.concurso.show.edit-concurso');
    }
}
