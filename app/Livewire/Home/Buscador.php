<?php

namespace App\Livewire\Home;

use App\Models\Usuarios\Sede;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Buscador extends Component
{
    use WithPagination;

    public $search;

    public $sedes;
    public $sede_id;

    public function updatingSearch() 
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->sedes = Sede::all();

        $sede = explode('.', $_SERVER['REMOTE_ADDR']);
        $this->sede_id = 1;
        if(isset($sede[2])) {
            if($sede[2] == 3) {
                $this->sede_id = 4;
            } elseif($sede[2] == 2) {
                $this->sede_id = 3;
            } elseif($sede[2] == 4) {
                $this->sede_id = 5;
            } elseif($sede[2] == 150) {
                $this->sede_id = 2;
            } 
        }
    }

    public function render()
    {
        $usuarios = User::where('visible', '1');

        if (strlen($this->search) >= 3) {
            $usuarios->where(function($query) {
                $query->where('realname', 'like', '%'.$this->search.'%')
                                    ->orWhere('interno', 'like', '%'.$this->search.'%');
            });
        }
        if($this->sede_id) {
            $usuarios->where('sede_id', $this->sede_id);
        }
        
        $usuarios = $usuarios->inRandomOrder()->paginate(10);

        return view('livewire.home.buscador', compact('usuarios'));
    }
}
