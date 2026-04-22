<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Registrador;
use App\Services\ProcesadorPrn;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CargaAutomatica extends Component
{
    public array $tabla = [];

    public function inicializarTabla(array $nombres): void
    {
        $this->tabla = [];
        foreach ($nombres as $nombre) {
            $this->tabla[$nombre] = [
                'nombre'     => $nombre,
                'estado'     => 'pendiente',
                'mensaje'    => '',
                'insertados' => 0,
                'errores'    => 0,
            ];
        }
    }

    public function procesarArchivo(string $nombre, string $base64): void
    {
        $this->tabla[$nombre]['estado'] = 'ejecutando';

        $contenido = base64_decode($base64);

        $procesador = app(ProcesadorPrn::class);

        // Detectar registrador desde el header del archivo
        $codigoReg = $procesador->detectarCodigoRegistrador($contenido);

        if (!$codigoReg) {
            $this->tabla[$nombre]['estado']  = 'error';
            $this->tabla[$nombre]['mensaje'] = 'No se encontró header válido';
            return;
        }

        $reg = Registrador::where('codigo', $codigoReg)->first();

        if (!$reg) {
            $this->tabla[$nombre]['estado']  = 'error';
            $this->tabla[$nombre]['mensaje'] = "Registrador no encontrado: {$codigoReg}";
            Log::warning("CargaAutomatica: registrador '{$codigoReg}' no encontrado. Archivo: {$nombre}");
            return;
        }

        $resultado = $procesador->procesar($contenido, $reg, $nombre);

        $this->tabla[$nombre]['estado']     = 'finalizado';
        $this->tabla[$nombre]['insertados'] = $resultado['insertados'];
        $this->tabla[$nombre]['errores']    = $resultado['errores'];
    }

    public function resetTodo(): void
    {
        $this->tabla = [];
    }

    public function render()
    {
        return view('livewire.despacho.carga-automatica');
    }
}