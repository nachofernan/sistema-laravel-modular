<?php

namespace App\Livewire\Concursos\Concurso\Show;

use App\Helpers\EmailHelper;
use App\Models\Concursos\Concurso;
use App\Models\Usuarios\ManagedJob;
use App\Services\ConcursoEncryptionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AccionesConcurso extends Component
{
    public $open = false;
    public $concurso;
    public $test;

    public function mount(Concurso $concurso) {
        $this->concurso = $concurso;
    }

    public function delete()
    {
        // Se borran todos los archivos
        // Crear el modelo del mail y el mail
        //Mail::to(['ifernandez@ccasa.com.ar'])->send(new ConcursoAbierto($this->concurso));
        if($this->test == "clave") {
            // Cancelar todos los jobs programados antes de eliminar
            $this->cancelarEmailsProgramados();
            
            $this->concurso->estado_id = 5;
            $this->concurso->save();
            // Crear el historial
            $this->concurso->historial()->create([
                'estado_id' => 5,
                'user_id' => Auth::id(),
            ]);
            return redirect()->route('concursos.concursos.show', $this->concurso);
        }
    }

    public function actualizarEstado($estado_id) {
        // Cancelar jobs existentes al cambiar estado
        EmailHelper::cancelarEmailsEntidad('concurso', $this->concurso->id);
        
        $this->concurso->estado_id = $estado_id;
        $this->concurso->save();
        
        // Crear el historial
        $this->concurso->historial()->create([
            'estado_id' => $estado_id,
            'user_id' => Auth::id(),
        ]);
        
        switch ($estado_id) {
            case '2': //Activo
               /*  $lastNum = Concurso::orderBy('numero', 'desc')->first();
                $this->concurso->numero = ($lastNum->numero > 0) ? $lastNum->numero + 1 : 1;
                $this->concurso->save(); */
                
                // Obtener proveedores invitados
                $proveedores = $this->concurso->obtenerProveedoresInvitados();

                // Notificación inmediata de apertura (con tracking por si se cancela)
                EmailHelper::notificarAperturaConcurso($this->concurso, $proveedores);

                // Programar emails automáticos (recordatorio + cierre)
                EmailHelper::programarEmailsAutomaticosConcurso($this->concurso, $proveedores);
                break;
                
            case '3': //En análisis
                // Desencriptar masivamente todos los documentos de ofertas válidas
                try {
                    $encryptionService = new ConcursoEncryptionService();
                    $result = $encryptionService->bulkDecryptOfertas($this->concurso->id);
                    
                    Log::info('Desencriptación masiva completada', [
                        'concurso_id' => $this->concurso->id,
                        'documentos_desencriptados' => $result['decrypted_count'],
                        'errores' => $result['errors']
                    ]);
                    
                    // Mostrar mensaje de éxito
                    session()->flash('success', "Se desencriptaron {$result['decrypted_count']} documentos y se limpiaron ofertas no presentadas.");
                    
                } catch (\Exception $e) {
                    Log::error('Error en desencriptación masiva', [
                        'concurso_id' => $this->concurso->id,
                        'error' => $e->getMessage()
                    ]);
                    
                    session()->flash('error', 'Error al desencriptar documentos: ' . $e->getMessage());
                }
                
                // Obtener proveedores que participaron (intencion != 2)
                $proveedores = $this->concurso->obtenerProveedoresParticipantes();
            
                // Notificación inmediata de finalización
                EmailHelper::notificarFinalizacionConcurso($this->concurso, $proveedores);
                break;
        }
        
        return redirect()->route('concursos.concursos.show', $this->concurso);
    }

    public function mailsRecordatorios() {

        $proveedores = $this->concurso->obtenerProveedoresParticipantes();
    
        // Recordatorio manual inmediato
        EmailHelper::enviarRecordatorioManual($this->concurso, $proveedores);
        
        $this->open = false;
    }

    /**
     * Cancelar todos los emails programados para este concurso
     */
    public function cancelarEmailsProgramados()
    {
        $cancelados = ManagedJob::cancelByEntity('concurso', $this->concurso->id);
        
        session()->flash('success', "Se cancelaron {$cancelados} emails programados para este concurso.");
        
        return redirect()->route('concursos.concursos.show', $this->concurso);
    }

    /**
     * Reprogramar emails cuando se cambia la fecha de cierre
     */
    public function reprogramarEmails()
    {
        $proveedores = $this->concurso->obtenerProveedoresParticipantes();
    
        // Una sola línea para cancelar y reprogramar todo
        EmailHelper::reprogramarEmailsConcurso($this->concurso, $proveedores);
        
        session()->flash('success', 'Emails reprogramados correctamente.');
        return redirect()->route('concursos.concursos.show', $this->concurso);
    }

    public function render()
    {
        return view('livewire.concursos.concurso.show.acciones-concurso');
    }
}