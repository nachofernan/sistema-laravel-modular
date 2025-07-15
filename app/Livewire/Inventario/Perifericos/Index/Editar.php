<?php

namespace App\Livewire\Inventario\Perifericos\Index;

use App\Models\Inventario\Periferico;
use Livewire\Component;

class Editar extends Component
{
    public $open = false;
    public $periferico;
    public $nombre;
    public $stock;

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

    public function mount(Periferico $periferico)
    {
        $this->periferico = $periferico;
        $this->nombre = $periferico->nombre;
        $this->stock = $periferico->stock;
    }

    public function update()
    {
        $this->validate();

        $this->periferico->update([
            'nombre' => $this->nombre,
            'stock' => $this->stock,
        ]);

        $this->open = false;
        
        session()->flash('message', 'Periférico actualizado exitosamente.');
        
        return redirect()->route('inventario.perifericos.index');
    }

    public function render()
    {
        return view('livewire.inventario.perifericos.index.editar');
    }
}