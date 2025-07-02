<?php

namespace App\Livewire\Concursos\Concurso;

use App\Models\Proveedores\Rubro;
use App\Models\Proveedores\Subrubro;
use Livewire\Component;

class Rubros extends Component
{
    public $open = false;

    public $concurso;
    public $rubros;
    public $subrubros = [];
    public $selectedRubro = null;
    public $selectedSubrubro = null;
    public $showSaveButton = false;

    public function mount($concurso)
    {
        $this->concurso = $concurso;
        $this->rubros = Rubro::all();
    }

    public function updatedSelectedRubro($rubroId)
    {
        $this->subrubros = Subrubro::where('rubro_id', $rubroId)->get();
        $this->selectedSubrubro = null;
        $this->showSaveButton = false;
    }

    public function updatedSelectedSubrubro($subrubroId)
    {
        $this->showSaveButton = !empty($subrubroId);
    }

    public function save()
    {
        // Aquí va la lógica para guardar la selección
        // Por ejemplo:
        // $this->emit('selectionSaved', $this->selectedRubro, $this->selectedSubrubro);
        $this->concurso->subrubro_id = $this->selectedSubrubro;
        $this->concurso->save();

        // Recarga la página
        return redirect()->to(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.concursos.concurso.rubros');
    }
}
