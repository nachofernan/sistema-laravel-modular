<?php

namespace App\Livewire\Tickets\Tickets\Index;

use App\Models\Tickets\Categoria;
use App\Models\Tickets\Estado;
use App\Models\Tickets\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;
    
    public $search = '';
    public $estados;
    public $estado_search = array();
    public $categoria = 0;
    public $categorias;


    public function mount()
    {
        $this->estados = Estado::all();
        $this->estado_search = [1];

        $this->categorias = Categoria::all();
    }

    public function estado_update($estado_id)
    {
        if(in_array($estado_id, $this->estado_search)) {
            unset($this->estado_search[array_search($estado_id, $this->estado_search)]);
        } else {
            $this->estado_search[] = $estado_id;
        }
    }

    public function refreshTickets($lastId)
    {
        if(Ticket::max('id') != $lastId) {
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if($this->search != '') {
            $tickets = Ticket::where('codigo', 'like', '%'.$this->search.'%');
        } else {
            $tickets = Ticket::whereIn('estado_id', $this->estado_search);
            if($this->categoria != 0) {
                $tickets = $tickets->where('categoria_id', $this->categoria);
            }
        }

        $lastId = Ticket::max('id');

        $tickets = $tickets->paginate(20);

        return view('livewire.tickets.tickets.index.search', compact('tickets', 'lastId'));
    }
}
