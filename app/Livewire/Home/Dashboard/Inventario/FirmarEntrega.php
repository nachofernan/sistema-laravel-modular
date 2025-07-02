<?php

namespace App\Livewire\Home\Dashboard\Inventario;

use Carbon\Carbon;
use Livewire\Component;

class FirmarEntrega extends Component
{
    public $open = false;
    public $elemento;

    public function mount($elemento)
    {
        $this->elemento = $elemento;
    }

    public function firmar()
    {
        $this->elemento->entregaActual()->update([
            'fecha_firma' => Carbon::now(),
        ]);

        return redirect()->route('home.dashboard');
    }

    public function render()
    {
        return view('livewire.home.dashboard.inventario.firmar-entrega');
    }
}
