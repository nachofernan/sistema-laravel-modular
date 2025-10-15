<?php

namespace App\Livewire\Capacitaciones\Capacitacions\Show;

use App\Models\Capacitaciones\Invitacion;
use App\Models\User;
use App\Models\Usuarios\Sede;
use Livewire\Component;

class Invitaciones extends Component
{
    public $capacitacion;
    public $open = false;
    public $selectedUsers = [];
    public $search = '';
    public $sedeId = '';

    public function agregar()
    {
        if(count($this->selectedUsers) > 0) {
            foreach($this->selectedUsers as $userId) {
                Invitacion::create([
                    'user_id' => $userId,
                    'capacitacion_id' => $this->capacitacion->id,
                    'tipo' => 'presencial', // Por defecto presencial
                ]);
            }
            $this->selectedUsers = [];
            $this->open = false;
        }
    }

    public function quitar($invitacion_id)
    {
        Invitacion::destroy($invitacion_id);
    }

    public function presente($invitacion_id)
    {
        $invitacion = Invitacion::find($invitacion_id);
        $invitacion->presente ? $invitacion->update(['presente' => '0']) : $invitacion->update(['presente' => '1']);
    }

    public function cambiarTipo($invitacion_id, $nuevoTipo)
    {
        $invitacion = Invitacion::find($invitacion_id);
        $invitacion->update(['tipo' => $nuevoTipo]);
    }

    public function render()
    {
        $invitados = $this->capacitacion->invitaciones->pluck('user_id');
        $query = User::whereNotIn('id', $invitados)
            ->where('legajo', '>', 0);

        if(trim($this->search) !== '') {
            $texto = trim($this->search);
            $query->where(function($q) use ($texto) {
                $q->where('legajo', 'like', "%$texto%")
                  ->orWhere('realname', 'like', "%$texto%");
            });
        }

        if(!empty($this->sedeId)) {
            $query->where('sede_id', $this->sedeId);
        }

        $usuarios = $query->orderBy('legajo')->get();
        $sedes = Sede::orderBy('nombre')->get();

        return view('livewire.capacitaciones.capacitacions.show.invitaciones', compact('usuarios', 'sedes'));
    }
}
