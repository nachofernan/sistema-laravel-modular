<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MisCapacitaciones extends Component
{
    public $capacitaciones;
    
    public function mount()
    {
        $this->loadCapacitaciones();
    }
    
    public function loadCapacitaciones()
    {
        $this->capacitaciones = User::find(Auth::id())->invitaciones()->get();
    }
    
    public function render()
    {
        return view('livewire.dashboard.mis-capacitaciones');
    }
}