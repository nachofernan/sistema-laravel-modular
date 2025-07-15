<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MiInventario extends Component
{
    public $elementos;
    
    protected $listeners = ['inventarioActualizado' => 'cargarElementos'];
    
    public function mount()
    {
        $this->cargarElementos();
    }
    
    public function cargarElementos()
    {
        $this->elementos = User::find(Auth::id())->elementos();
    }
    
    public function firmarEntrega($elementoId)
    {
        $elemento = $this->elementos->find($elementoId);
        
        if (!$elemento || 
            !$elemento->entregaActual() || 
            $elemento->entregaActual()->user_id !== Auth::id() ||
            $elemento->entregaActual()->fecha_devolucion ||
            $elemento->entregaActual()->fecha_firma) {
            return;
        }
        
        $elemento->entregaActual()->update([
            'fecha_firma' => now()
        ]);
        
        $this->cargarElementos();
        
        session()->flash('mensaje_verde', 'Entrega firmada correctamente');
    }
    
    public function render()
    {
        return view('livewire.dashboard.mi-inventario');
    }
}