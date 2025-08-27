<?php

namespace App\Livewire\Automotores\Copres\Delete;

use App\Models\Automotores\Copres;
use Livewire\Component;

class Modal extends Component
{
    public $showModal = false;
    public $copresId;
    public $copres;

    protected $listeners = ['openDeleteModal' => 'openModal'];

    public function openModal($copresId)
    {
        $this->copresId = $copresId;
        $this->copres = Copres::with(['vehiculo'])->findOrFail($copresId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['copresId', 'copres']);
    }

    public function confirmDelete()
    {
        $this->copres->delete();
        $this->closeModal();
        session()->flash('info', 'COPRES eliminada exitosamente');
        return redirect()->route('automotores.copres.index');
    }

    public function render()
    {
        return view('livewire.automotores.copres.delete.modal');
    }
}
