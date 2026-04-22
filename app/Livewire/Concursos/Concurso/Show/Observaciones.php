<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Models\Concursos\Invitacion;
use Livewire\Component;

class Observaciones extends Component
{
    public $open = false;
    public $concurso;
    public $invitacion;
    public $observaciones;

    protected $rules = [
        'observaciones' => 'nullable|string',
    ];

    public function mount($concurso, $invitacion)
    {
        $this->concurso = $concurso;
        $this->invitacion = $invitacion;
        $this->observaciones = $invitacion->observaciones;
    }

    public function save()
    {
        // Verificar permisos: gestor (owner) o tiene permiso de edición (admin)
        if (!($this->concurso->user_id === auth()->id() || auth()->user()->can('Concursos/Concursos/Editar'))) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No tiene permisos para editar observaciones.']);
            return;
        }

        // Verificar estado del concurso (Análisis = 3)
        if ($this->concurso->estado_id != 3) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Solo se pueden editar observaciones en estado de Análisis.']);
            return;
        }

        $this->validate();

        $this->invitacion->update([
            'observaciones' => $this->observaciones,
        ]);

        $this->open = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Observaciones actualizadas correctamente.']);
        $this->dispatch('refreshInvitacion'); // Opcional, por si otros componentes necesitan enterarse
    }

    public function render()
    {
        return view('livewire.concursos.concurso.show.observaciones');
    }
}
