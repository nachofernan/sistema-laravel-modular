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

    public $estado_search = [];

    public $categoria = 0;

    public $categorias;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public function mount()
    {
        $this->estados = Estado::all();
        $this->estado_search = [1];

        $this->categorias = Categoria::all();
    }

    public function estado_update($estado_id)
    {
        if (in_array($estado_id, $this->estado_search)) {
            unset($this->estado_search[array_search($estado_id, $this->estado_search)]);
        } else {
            $this->estado_search[] = $estado_id;
        }
    }

    public function refreshTickets($lastId)
    {
        if (Ticket::max('id') != $lastId) {
            $this->resetPage();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $tickets = Ticket::query()
            ->whereIn('estado_id', $this->estado_search)
            ->when($this->categoria != 0, fn ($query) => $query->where('categoria_id', $this->categoria))
            ->when($this->search != '', fn ($query) => $query->where('codigo', 'like', '%'.$this->search.'%'))
            ->when(in_array($this->sortBy, ['categoria', 'estado']), function ($query) {
                $tabla = $this->sortBy === 'categoria' ? 'categorias' : 'estados';

                $query->leftJoin($tabla, "tickets.{$this->sortBy}_id", '=', "$tabla.id")
                    ->orderByRaw("$tabla.nombre IS NULL")
                    ->orderBy("$tabla.nombre", $this->sortDirection)
                    ->select('tickets.*');
            }, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->with(['categoria', 'estado', 'user', 'encargado', 'documento']);

        $lastId = Ticket::max('id');

        $tickets = $tickets->paginate(20);

        return view('livewire.tickets.tickets.index.search', compact('tickets', 'lastId'));
    }
}
