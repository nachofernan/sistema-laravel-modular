<?php

namespace App\Livewire\Proveedores\Proveedors\Show;

use App\Helpers\EmailHelper;
use App\Mail\Proveedores\EnviarNotificacionVencimiento;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class NotificarVencimiento extends Component
{

    public $open = false;
    public $proveedor;

    public function enviarNotificacion() {
        //Enviar mail a mesa de entradas
        //Mail::to(['ifernandez@ccasa.com.ar'])->send(new EnviarNotificacionVencimiento($this->proveedor));
        EmailHelper::enviarNotificacion(
            ['ssanchez@buenosairesenergia.com.ar', 'jpzacoutegui@buenosairesenergia.com.ar'],
            new EnviarNotificacionVencimiento($this->proveedor),
            'Notificación de vencimiento de documentos del proveedor: ' . $this->proveedor->razonsocial
        );
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.proveedores.proveedors.show.notificar-vencimiento');
    }
}
