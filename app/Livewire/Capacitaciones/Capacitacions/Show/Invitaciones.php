<?php

namespace App\Livewire\Capacitaciones\Capacitacions\Show;

use App\Models\Capacitaciones\Invitacion;
use App\Models\User;
use Livewire\Component;

class Invitaciones extends Component
{
    public $capacitacion;
    public $open = false;
    public $selectedUsers = [];

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
        $usuarios = User::whereNotIn('id', $invitados)->where('legajo', '>', 0)->orderBy('legajo')->get();
        return view('livewire.capacitaciones.capacitacions.show.invitaciones', compact('usuarios'));
    }
}
