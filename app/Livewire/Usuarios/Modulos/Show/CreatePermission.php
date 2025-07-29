<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Permission;
use Livewire\Component;

class CreatePermission extends Component
{
    public $open = false;
    public $modulo;
    public $nombre = '';

    public function mount($modulo)
    {
        $this->modulo = $modulo;
    }

    public function create()
    {
        // Validación básica
        if (empty($this->nombre)) {
            session()->flash('error', 'El nombre del permiso es requerido.');
            return;
        }

        $nombreCompleto = ucfirst($this->modulo->nombre) . "/" . $this->nombre;
        
        try {
            // Verificar si el permiso ya existe
            $existingPermission = Permission::where('name', $nombreCompleto)->first();
            if ($existingPermission) {
                session()->flash('error', 'Ya existe un permiso con ese nombre.');
                return;
            }

            Permission::create(['name' => $nombreCompleto]);
            
            session()->flash('message', 'Permiso creado exitosamente.');
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el permiso: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.create-permission');
    }
}