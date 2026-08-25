<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MisCapacitaciones extends Component
{
    public $capacitaciones;

    public function mount()
    {
        $this->loadCapacitaciones();
    }

    public function loadCapacitaciones()
    {
        $this->capacitaciones = User::find(Auth::id())->invitaciones()->with('capacitacion.encuestas.preguntas')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.mis-capacitaciones');
    }
}
