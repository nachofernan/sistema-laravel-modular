<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Lectura;
use App\Models\Despacho\Registrador;
use Illuminate\Support\Carbon;
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

        // Decodificar y escribir a temp
        $contenido = base64_decode($base64);
        $tmp = tempnam(sys_get_temp_dir(), 'prn_');
        file_put_contents($tmp, $contenido);

        $lineas = file($tmp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        unlink($tmp);

        // ── Buscar header ─────────────────────────────────────
        $headerIdx = null;
        $codigoReg = null;

        foreach ($lineas as $i => $linea) {
            $cols = $this->parsearCols($linea);
            if (isset($cols[0]) && strtolower(trim($cols[0])) === 'time') {
                $headerIdx = $i;
                $codigoReg = isset($cols[1]) ? trim($cols[1]) : null;
                break;
            }
        }

        if (!$codigoReg) {
            $this->tabla[$nombre]['estado']  = 'error';
            $this->tabla[$nombre]['mensaje'] = 'No se encontró header válido';
            return;
        }

        // ── Buscar registrador en BD ───────────────────────────
        $reg = Registrador::where('codigo', $codigoReg)->first();

        if (!$reg) {
            $this->tabla[$nombre]['estado']  = 'error';
            $this->tabla[$nombre]['mensaje'] = "Registrador no encontrado: {$codigoReg}";
            Log::warning("CargaAutomatica: registrador '{$codigoReg}' no encontrado. Archivo: {$nombre}");
            return;
        }

        $columnaIdx  = $reg->columna_datos - 1;
        $factor      = (float) $reg->factor_conversion;
        $insertados  = 0;
        $errores     = 0;
        $fechaActual = null;

        foreach ($lineas as $numLinea => $linea) {
            if ($numLinea <= $headerIdx) continue;

            $cols = $this->parsearCols($linea);

            if (!isset($cols[0]) || $this->esEncabezado($cols[0])) continue;

            [$fecha, $horaHasta, $esFinDia] = $this->parsearFechaHora($cols[0], $fechaActual);

            if ($fecha === null) {
                $errores++;
                Log::debug("CargaAutomatica [{$nombre}] línea {$numLinea}: no se pudo parsear '{$cols[0]}'");
                continue;
            }

            $fechaActual = $fecha;

            if (!isset($cols[$columnaIdx])) {
                $errores++;
                Log::debug("CargaAutomatica [{$nombre}] línea {$numLinea}: columna {$reg->columna_datos} no existe");
                continue;
            }

            $valorCrudo = $cols[$columnaIdx];

            if (!is_numeric($valorCrudo)) {
                $errores++;
                Log::debug("CargaAutomatica [{$nombre}] línea {$numLinea}: valor '{$valorCrudo}' no numérico");
                continue;
            }

            $valorCrudo      = (float) $valorCrudo;
            $valorConvertido = round($valorCrudo * $factor, 4);

            [$horaDesde, $bloqueHorario, $fechaLectura] = $this->calcularBloque($horaHasta, $fecha, $esFinDia);

            // Insertar evitando duplicados - búsqueda flexible para ambos formatos
            $horaHastaBusqueda = $horaHasta === '24:00' ? '00:00' : $horaHasta;
            $horaHastaGuardar  = $horaHasta === '24:00' ? '00:00:00' : $horaHasta . ':00';
            
            $existente = Lectura::where('registrador_id', $reg->id)
                ->where('fecha', $fechaLectura)
                ->where('bloque_horario', $bloqueHorario)
                ->where(function($query) use ($horaHastaBusqueda, $horaHastaGuardar) {
                    $query->where('hora_hasta', $horaHastaBusqueda)
                          ->orWhere('hora_hasta', $horaHastaGuardar);
                })
                ->first();

            if ($existente) {
                $existente->update([
                    'hora_desde'       => $horaDesde,
                    'valor_crudo'      => $valorCrudo,
                    'valor_convertido' => $valorConvertido,
                ]);
            } else {
                Lectura::create([
                    'registrador_id'    => $reg->id,
                    'fecha'             => $fechaLectura,
                    'bloque_horario'    => $bloqueHorario,
                    'hora_hasta'        => $horaHastaGuardar,
                    'hora_desde'        => $horaDesde,
                    'valor_crudo'       => $valorCrudo,
                    'valor_convertido'  => $valorConvertido,
                ]);
            }
            $insertados++;
        }

        $this->tabla[$nombre]['estado']     = 'finalizado';
        $this->tabla[$nombre]['insertados'] = $insertados;
        $this->tabla[$nombre]['errores']    = $errores;

        if ($errores > 0) {
            Log::warning("CargaAutomatica [{$nombre}]: {$insertados} insertados, {$errores} errores.");
        }
    }

    public function resetTodo(): void
    {
        $this->tabla = [];
    }

    // ── Helpers ───────────────────────────────────────────────

    protected function parsearCols(string $linea): array
    {
        return array_map('trim', str_getcsv(trim($linea)));
    }

    protected function esEncabezado(string $valor): bool
    {
        return !preg_match('/^\s*\d/', trim($valor));
    }

    protected function parsearFechaHora(string $valor, ?Carbon $fechaActual): array
    {
        $valor = trim($valor);

        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})\s+(\d{1,2}):(\d{2})$/', $valor, $m)) {
            $anio     = (int)$m[3] < 100 ? (int)$m[3] + 2000 : (int)$m[3];
            $fecha    = Carbon::createFromDate($anio, (int)$m[1], (int)$m[2]);
            $esFinDia = ((int)$m[4] === 24 && (int)$m[5] === 0);
            return [$fecha, sprintf('%02d:%02d', (int)$m[4], (int)$m[5]), $esFinDia];
        }

        if (preg_match('/^(\d{1,2}):(\d{2})$/', $valor, $m) && $fechaActual !== null) {
            $esFinDia = ((int)$m[1] === 24 && (int)$m[2] === 0);
            return [$fechaActual->copy(), sprintf('%02d:%02d', (int)$m[1], (int)$m[2]), $esFinDia];
        }

        return [null, null, false];
    }

    protected function calcularBloque(string $horaHasta, Carbon $fecha, bool $esFinDia): array
    {
        if ($esFinDia) {
            return ['23:45:00', 23, $fecha->copy()];
        }

        [$h, $m]   = explode(':', $horaHasta);
        $totalMin  = (int)$h * 60 + (int)$m - 15;
        $horaDesde = sprintf('%02d:%02d:00', intdiv($totalMin, 60), $totalMin % 60);

        return [$horaDesde, intdiv($totalMin, 60), $fecha->copy()];
    }

    public function render()
    {
        return view('livewire.despacho.carga-automatica');
    }
}