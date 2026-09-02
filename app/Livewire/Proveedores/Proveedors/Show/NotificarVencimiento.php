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
        EmailHelper::enviarNotificacion(
            config('notificaciones.proveedores.vencimiento_documentos'),
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
