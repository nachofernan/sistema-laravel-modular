<?php
// app/Livewire/Proveedores/DocumentoTipos/EditModal.php

namespace App\Livewire\Proveedores\DocumentoTipos;

use App\Models\Proveedores\DocumentoTipo;
use Livewire\Component;

class EditModal extends Component
{
    public $open = false;
    public $documentoTipo;
    
    // Propiedades del formulario
    public $codigo;
    public $nombre;
    public $vencimiento;

    protected $rules = [
        'codigo' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'vencimiento' => 'required|boolean',
    ];

    protected $messages = [
        'codigo.required' => 'El código es obligatorio.',
        'codigo.max' => 'El código no puede tener más de 255 caracteres.',
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
        'vencimiento.required' => 'Debe especificar si tiene vencimiento.',
    ];

    public function mount(DocumentoTipo $documentoTipo)
    {
        $this->documentoTipo = $documentoTipo;
        $this->codigo = $documentoTipo->codigo;
        $this->nombre = $documentoTipo->nombre;
        $this->vencimiento = $documentoTipo->vencimiento ? 1 : 0;
    }

    public function update()
    {
        $this->validate();

        try {
            $this->documentoTipo->update([
                'codigo' => $this->codigo,
                'nombre' => $this->nombre,
                'vencimiento' => (bool) $this->vencimiento,
            ]);

            $this->open = false;
            
            // Opcional: Emitir evento para actualizar la lista o mostrar notificación
            $this->dispatch('documentoTipoUpdated');
            
            // Opcional: Mostrar mensaje de éxito
            session()->flash('message', 'Tipo de documento actualizado correctamente.');
            
        } catch (\Exception $e) {
            // Manejar errores
            session()->flash('error', 'Error al actualizar el tipo de documento.');
        }
    }

    public function render()
    {
        return view('livewire.proveedores.documento-tipos.edit-modal');
    }
}