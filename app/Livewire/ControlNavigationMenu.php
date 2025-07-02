<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ControlNavigationMenu extends Component
{
    public $modulo;

    public function mount()
    {
        $this->modulo = request()->attributes->get('module', 'guest');
    }

    public function render()
    {
        return view('livewire.control-navigation-menu', [
            'modulo' => $this->modulo,
        ]);
    }
}
