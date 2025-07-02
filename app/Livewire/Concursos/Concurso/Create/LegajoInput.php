<?php

namespace App\Livewire\Concursos\Concurso\Create;

use Livewire\Component;

class LegajoInput extends Component
{
    public $numero_legajo;

    public function mount($numero_legajo = 'LE-____-_')
    {
        $this->numero_legajo = $numero_legajo ?? 'LE-____-_';
    }

    public function updatedNumeroLegajo($value)
    {
        $numericValue = preg_replace('/[^0-9]/', '', $value);

        // Asegurar que tiene al menos 5 caracteres (para evitar que ponga solo el año)
        if (strlen($numericValue) < 5) {
            return;
        }

        // Extraer el año (primeros 4 dígitos)
        $year = substr($numericValue, 0, 4);

        // Extraer el número correlativo (lo que sigue)
        $correlativo = substr($numericValue, 4);

        // Construir el formato final
        $this->numero_legajo = "LE-{$year}-{$correlativo}";

        // Emitir evento a otros Livewire Components
        $this->dispatch('actualizarNumeroLegajo', numero_legajo: $this->numero_legajo);
    }

    public function render()
    {
        return view('livewire.concursos.concurso.create.legajo-input');
    }
}
