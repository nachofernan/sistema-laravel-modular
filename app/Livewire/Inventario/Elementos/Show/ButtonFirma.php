<?php

namespace App\Livewire\Inventario\Elementos\Show;

use App\Models\Inventario\Entrega;
use Carbon\Carbon;
use Livewire\Component;

class ButtonFirma extends Component
{
    public $open = false;
    public $elemento;
    public $color;
    public $texto_boton;
    public $texto_descripcion;
    public $texto_accion;

    public function mount($elemento) 
    {
        $this->elemento = $elemento;
        if (!$this->elemento->entregaActual()) {
            $this->color = 'blue';
            $this->texto_boton = 'Marcar Entrega';
            $this->texto_descripcion = 'Marcar entrega y solicitar firma del usuario ' . $elemento->user->realname . ' para el elemento ' . $elemento->codigo;
        } elseif($this->elemento->entregaActual()->fecha_firma) {
            $this->color = 'green';
            $this->texto_boton = 'Firmado';
            $this->texto_descripcion = 'Haga clic en el siguiente botón si el usuario ya ha devuelto el elemento ' . $elemento->codigo;
        } else {
            $this->color = 'yellow';
            $this->texto_boton = 'Esperando Firma';
            $this->texto_descripcion = 'Cancelar Firma';
        }
    }

    public function activar($accion)
    {
        switch ($accion) {
            case '1':
                Entrega::create([
                    'elemento_id' => $this->elemento->id,
                    'user_id' => $this->elemento->user_id,
                    'fecha_entrega' => Carbon::now(),
                ]);
                break;
            case '2':
                $this->elemento->entregaActual()->update([
                    'fecha_devolucion' => Carbon::now(),
                ]);
                $this->elemento->update([
                    'user_id' => null,
                ]);
                break;
            case '3':
                $this->elemento->entregaActual()->delete();
                $this->elemento->update([
                    'user_id' => null,
                ]);
                break;
            default:
                break;
        }

        return redirect()->route('inventario.elementos.show', $this->elemento);
    }

    public function render()
    {
        return view('livewire.inventario.elementos.show.button-firma');
    }
}
