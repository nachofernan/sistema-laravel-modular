<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MisTickets extends Component
{
    public $ticketsAbiertos;
    public $ticketsCerrados;
    
    protected $listeners = ['ticketActualizado' => 'actualizarTickets'];
    
    public function mount()
    {
        $this->actualizarTickets();
    }
    
    public function actualizarTickets()
    {
        $this->ticketsAbiertos = User::find(Auth::id())->ticketsAbiertos();
        $this->ticketsCerrados = User::find(Auth::id())->ticketsCerrados();
    }
    
    public function abrirTicket($ticketId)
    {
        $this->dispatch('abrirTicket', $ticketId);
    }
    
    public function render()
    {
        return view('livewire.dashboard.mis-tickets');
    }
}