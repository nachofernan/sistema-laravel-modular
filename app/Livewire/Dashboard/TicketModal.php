<?php

namespace App\Livewire\Dashboard;

use App\Models\Tickets\Mensaje;
use App\Models\Tickets\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TicketModal extends Component
{
    public $showModal = false;
    public $ticket;
    public $nuevoMensaje = '';
    
    protected $listeners = ['abrirTicket'];
    
    protected $rules = [
        'nuevoMensaje' => 'required|min:3|max:500'
    ];
    
    protected $messages = [
        'nuevoMensaje.required' => 'El mensaje es obligatorio',
        'nuevoMensaje.min' => 'El mensaje debe tener al menos 3 caracteres',
        'nuevoMensaje.max' => 'El mensaje no puede exceder 500 caracteres'
    ];
    
    public function abrirTicket($ticketId)
    {
        $this->ticket = Ticket::with(['categoria', 'estado', 'user', 'mensajes.user'])
            ->findOrFail($ticketId);
            
        // Marcar mensajes como leídos si el usuario es el propietario del ticket
        if ($this->ticket->user_id === Auth::id()) {
            $this->ticket->mensajes()
                ->where('user_id', '!=', Auth::id())
                ->where('leido', false)
                ->update(['leido' => true]);
        }
        
        $this->showModal = true;
        $this->nuevoMensaje = '';
    }
    
    public function cerrarModal()
    {
        $this->showModal = false;
        $this->ticket = null;
        $this->nuevoMensaje = '';
        $this->resetErrorBag();
    }
    
    public function enviarMensaje()
    {
        $this->validate();
        
        if (!$this->ticket || $this->ticket->finalizado) {
            $this->addError('nuevoMensaje', 'No se puede enviar mensajes a un ticket cerrado');
            return;
        }
        
        Mensaje::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'mensaje' => $this->nuevoMensaje,
            'leido' => false
        ]);
        
        $this->nuevoMensaje = '';
        
        // Recargar el ticket con los mensajes actualizados
        $this->ticket->refresh();
        $this->ticket->load(['mensajes.user']);
        
        $this->dispatch('ticketActualizado');
        
        session()->flash('mensaje_verde', 'Mensaje enviado correctamente');
    }
    
    public function render()
    {
        return view('livewire.dashboard.ticket-modal');
    }
}