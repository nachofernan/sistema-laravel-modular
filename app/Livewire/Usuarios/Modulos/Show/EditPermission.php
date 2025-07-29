<?php

namespace App\Livewire\Usuarios\Modulos\Show;

use App\Models\Usuarios\Permission;
use Livewire\Component;

class EditPermission extends Component
{
    public $open = false;
    public $modulo;
    public $permiso;
    public $nombre = '';
    public $test = '';

    public function mount($modulo, $permiso)
    {
        $this->modulo = $modulo;
        $this->permiso = $permiso;
        
        // Extraer la parte del nombre sin el módulo
        $parts = explode('/', $permiso->name, 2);
        $this->nombre = isset($parts[1]) ? $parts[1] : '';
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
            // Verificar si el nuevo nombre ya existe (excepto el actual)
            $existingPermission = Permission::where('name', $nombreCompleto)
                                           ->where('id', '!=', $this->permiso->id)
                                           ->first();
            if ($existingPermission) {
                session()->flash('error', 'Ya existe un permiso con ese nombre.');
                return;
            }

            // Actualizar el nombre del permiso
            $this->permiso->update(['name' => $nombreCompleto]);

            session()->flash('message', 'Permiso actualizado exitosamente.');
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el permiso: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->test !== "1234") {
            session()->flash('error', 'Código de confirmación incorrecto.');
            return;
        }

        try {
            // Verificar si el permiso está siendo usado por roles
            $rolesCount = $this->permiso->roles()->count();
            if ($rolesCount > 0) {
                session()->flash('error', "No se puede eliminar el permiso. Está asignado a {$rolesCount} rol(es).");
                return;
            }

            $this->permiso->delete();
            session()->flash('message', 'Permiso eliminado exitosamente.');
            return redirect()->route('usuarios.modulos.show', $this->modulo);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el permiso: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.usuarios.modulos.show.edit-permission');
    }
}