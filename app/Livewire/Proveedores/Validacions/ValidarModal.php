<?php

namespace App\Livewire\Proveedores\Validacions;

use App\Helpers\EmailHelper;
use App\Mail\Proveedores\RechazarArchivoValidacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ValidarModal extends Component
{
    public $open = false;
    public $validacion;
    public $vencimiento = null;
    public $comentarios = "";

    public function mount($validacion) {
        $this->validacion = $validacion;
        $this->vencimiento = $validacion->documento->tieneVencimiento() ? $validacion->documento->vencimiento->format('Y-m-d') : null;
    }

    public function validar() {
        $this->validacion->validado = true;
        $this->validacion->user_id = Auth::user()->id;
        $this->validacion->save();
        $this->validacion->documento->vencimiento = $this->vencimiento;
        $this->validacion->documento->save();
        return redirect()->route('proveedores.validacions.index');
    }

    public function rechazar() {
        if($this->comentarios == "") {
            $this->addError('comentarios', 'El campo comentarios es obligatorio');
            return;
        }
        $documento = $this->validacion->documento;
        if(Storage::disk('proveedores')->exists($documento->file_storage)) {
            Storage::disk('proveedores')->delete($documento->file_storage);
        }
        $this->validacion->comentarios = $this->comentarios;
        $this->validacion->save();
        if($documento->creador->id > 1) {
            if($documento->creador->email != null) {
                //Mail::to($documento->creador->email)->send(new RechazarArchivoValidacion($documento->validacion));
                EmailHelper::enviarNotificacion(
                    [$documento->creador->email],
                    new RechazarArchivoValidacion($documento->validacion),
                    'Validación rechazada'
                );
            }
        }
        /* $this->validacion->delete();
        if($documento->documentable_type == 'App\Models\Proveedores\Apoderado') {
            if(count($documento->documentable->documentos) == 1) {
                $documento->documentable->delete();
            }
        }
        $documento->delete(); */
        return redirect()->route('proveedores.validacions.index');
    }

    public function render()
    {
        return view('livewire.proveedores.validacions.validar-modal');
    }
}
