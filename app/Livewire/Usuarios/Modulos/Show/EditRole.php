<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use Livewire\Component;

class EditRole extends Component
{
    public $open = false;
    public $modulo;
    public $role;
    public $nombre = '';
    public $test = '';
    public $permisos = [];

    public function mount($modulo, $role)
    {
        $this->modulo = $modulo;
        $this->role = $role;
        
        // Extraer la parte del nombre sin el módulo
        $parts = explode('/', $role->name, 2);
        $this->nombre = isset($parts[1]) ? $parts[1] : '';
        
        // Cargar permisos actuales del rol
        $this->permisos = $role->permissions->pluck('name')->toArray();
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
            // Verificar si el nuevo nombre ya existe (excepto el actual)
            $existingRole = \App\Models\Usuarios\Role::where('name', $nombreCompleto)
                                                    ->where('id', '!=', $this->role->id)
                                                    ->first();
            if ($existingRole) {
                session()->flash('error', 'Ya existe un rol con ese nombre.');
                return;
            }

            // Actualizar el nombre del rol
            $this->role->update(['name' => $nombreCompleto]);
            
            // Sincronizar permisos
            $this->role->syncPermissions($this->permisos);

            session()->flash('message', 'Rol actualizado exitosamente.');
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->test !== "1234") {
            session()->flash('error', 'Código de confirmación incorrecto.');
            return;
        }

        try {
            // Verificar si el rol está siendo usado por usuarios
            $usersCount = $this->role->users()->count();
            if ($usersCount > 0) {
                session()->flash('error', "No se puede eliminar el rol. Está asignado a {$usersCount} usuario(s).");
                return;
            }

            $this->role->delete();
            session()->flash('message', 'Rol eliminado exitosamente.');
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el rol: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.edit-role');
    }
}