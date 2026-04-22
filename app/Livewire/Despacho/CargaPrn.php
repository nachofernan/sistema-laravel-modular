<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Registrador;
use App\Services\ProcesadorPrn;
use Livewire\Component;
use Livewire\WithFileUploads;

class CargaPrn extends Component
{
    use WithFileUploads;

    public ?int    $registrador_id  = null;
    public         $archivo         = null;

    public bool    $procesando      = false;
    public ?string $resultadoMsg    = null;
    public string  $resultadoTipo   = 'info';

    public array   $erroresLinea    = [];
    public int     $insertados      = 0;
    public int     $duplicados      = 0;
    public int     $errorCount      = 0;

    public ?array  $infoRegistrador = null;

    // ── Computed ──────────────────────────────────────────────
    public function getRegistradoresProperty()
    {
        return Registrador::where('activo', true)
            ->orderBy('codigo')
            ->orderBy('tipo')
            ->get();
    }

    // ── Watchers ──────────────────────────────────────────────
    public function updatedRegistradorId(): void
    {
        $this->resetResultado();

        if (!$this->registrador_id) {
            $this->infoRegistrador = null;
            return;
        }

        $reg = Registrador::find($this->registrador_id);
        if (!$reg) return;

        $this->infoRegistrador = [
            'codigo'            => $reg->codigo,
            'tipo_dato'         => $reg->tipo_dato,
            'columna_datos'     => $reg->columna_datos,
            'factor_conversion' => $reg->factor_conversion,
            'maquinas'          => $reg->maquinas->pluck('codigo')->join(', '),
        ];
    }

    // ── Procesamiento ─────────────────────────────────────────
    public function procesar(): void
    {
        $this->validate([
            'registrador_id' => ['required', 'exists:despacho.registradores,id'],
            'archivo'        => ['required', 'file', 'max:10240'],
        ]);

        $this->procesando = true;
        $this->resetResultado();

        $reg       = Registrador::findOrFail($this->registrador_id);
        $contenido = file_get_contents($this->archivo->getRealPath());

        $resultado = app(ProcesadorPrn::class)->procesar($contenido, $reg, $this->archivo->getClientOriginalName());

        $this->insertados   = $resultado['insertados'];
        $this->errorCount   = $resultado['errores'];
        $this->erroresLinea = array_slice($resultado['detalle'], 0, 50);

        if ($this->insertados > 0) {
            $this->resultadoTipo = $this->errorCount > 0 ? 'warning' : 'success';
            $this->resultadoMsg  = 'Proceso finalizado.';
        } else {
            $this->resultadoTipo = 'error';
            $this->resultadoMsg  = 'No se insertó ningún registro.';
        }

        $this->procesando = false;
        $this->archivo    = null;
    }

    protected function resetResultado(): void
    {
        $this->resultadoMsg  = null;
        $this->resultadoTipo = 'info';
        $this->erroresLinea  = [];
        $this->insertados    = 0;
        $this->duplicados    = 0;
        $this->errorCount    = 0;
    }

    // ── Render ────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.despacho.carga-prn', [
            'registradores' => $this->registradores,
        ]);
    }
}