<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Role;
use Livewire\Component;

class CreateRole extends Component
{
    public $open = false;
    public $modulo;
    public $nombre = '';
    public $permisos = [];

    public function mount($modulo)
    {
        $this->modulo = $modulo;
    }

    public function toggleAllPermisos()
    {
        $allPermisos = $this->modulo->permisos()->pluck('name')->toArray();
        
        if (count($this->permisos) == count($allPermisos)) {
            // Si todos están seleccionados, deseleccionar todos
            $this->permisos = [];
        } else {
            // Si no todos están seleccionados, seleccionar todos
            $this->permisos = $allPermisos;
        }
    }

    public function create()
    {
        // Validación básica
        if (empty($this->nombre)) {
            session()->flash('error', 'El nombre del rol es requerido.');
            return;
        }

        $nombreCompleto = ucfirst($this->modulo->nombre) . "/" . $this->nombre;
        
        try {
            // Verificar si el rol ya existe
            $existingRole = Role::where('name', $nombreCompleto)->first();
            if ($existingRole) {
                session()->flash('error', 'Ya existe un rol con ese nombre.');
                return;
            }

            $role = Role::create(['name' => $nombreCompleto]);
            
            // Sincronizar permisos si se seleccionaron
            if (!empty($this->permisos)) {
                $role->syncPermissions($this->permisos);
            }

            session()->flash('message', 'Rol creado exitosamente.');
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el rol: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.create-role');
    }
}