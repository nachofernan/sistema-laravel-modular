<?php

namespace App\Livewire\Inventario\Perifericos\Index;

use App\Models\Inventario\Periferico;
use Livewire\Component;

class Crear extends Component
{
    public $open = false;
    public $nombre;
    public $stock = 0;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre del periférico es obligatorio.',
        'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
        'stock.required' => 'El stock es obligatorio.',
        'stock.integer' => 'El stock debe ser un número entero.',
        'stock.min' => 'El stock no puede ser negativo.',
    ];

    public function store()
    {
        $this->validate();

        Periferico::create([
            'nombre' => $this->nombre,
            'stock' => $this->stock,
        ]);

        $this->reset(['nombre', 'stock', 'open']);
        
        session()->flash('message', 'Periférico creado exitosamente.');
        
        return redirect()->route('inventario.perifericos.index');
    }

    public function render()
    {
        return view('livewire.inventario.perifericos.index.crear');
    }
}