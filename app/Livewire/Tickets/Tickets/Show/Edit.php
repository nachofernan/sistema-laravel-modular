<?php

namespace App\Livewire\Tickets\Tickets\Show;

use App\Models\Tickets\Categoria;
use App\Models\Tickets\Estado;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{

    public $open = false;
    public $ticket;

    public function render()
    {
        $categorias = Categoria::all();
        $estados = Estado::all();
        $users = User::role('Tickets/Acceso')->get();

        return view('livewire.tickets.tickets.show.edit', compact('categorias', 'estados', 'users'));
    }
}
