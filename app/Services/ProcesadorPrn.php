<?php

namespace App\Services;

use App\Models\Despacho\Lectura;
use App\Models\Despacho\Registrador;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ProcesadorPrn
{
    /**
     * Procesa el contenido de un archivo PRN para un registrador dado.
     * Usado por CargaPrn (registrador conocido de antemano) y por
     * CargaAutomatica (registrador detectado desde el header del archivo).
     *
     * @param  string          $contenido   Contenido crudo del archivo
     * @param  Registrador     $reg         Registrador destino
     * @param  string|null     $contexto    Nombre del archivo (para logs)
     * @return array{insertados: int, errores: int, detalle: string[]}
     */
    public function procesar(string $contenido, Registrador $reg, ?string $contexto = null): array
    {
        $lineas      = $this->splitLineas($contenido);
        $columnaIdx  = $reg->columna_datos - 1;
        $factor      = (float) $reg->factor_conversion;

        $insertados  = 0;
        $errores     = 0;
        $detalle     = [];
        $fechaActual = null;

        // Detectar y saltear header si existe (línea con "time" en col 0)
        $headerIdx = null;
        foreach ($lineas as $i => $linea) {
            $cols = $this->parsearCols($linea);
            if (isset($cols[0]) && strtolower(trim($cols[0])) === 'time') {
                $headerIdx = $i;
                break;
            }
        }

        foreach ($lineas as $numLinea => $linea) {
            if ($headerIdx !== null && $numLinea <= $headerIdx) continue;

            $cols    = $this->parsearCols($linea);
            $colCero = $this->limpiarColCero($cols[0] ?? '');

            if (!$colCero || $this->esEncabezado($colCero)) continue;

            [$fecha, $horaHasta, $esFinDia] = $this->parsearFechaHora($colCero, $fechaActual);

            if ($fecha === null) {
                $errores++;
                $detalle[] = "Línea " . ($numLinea + 1) . ": no se pudo parsear '{$colCero}'";
                Log::debug("ProcesadorPrn [{$contexto}] línea {$numLinea}: no se pudo parsear '{$colCero}'");
                continue;
            }

            $fechaActual = $fecha;

            if (!isset($cols[$columnaIdx])) {
                $errores++;
                $detalle[] = "Línea " . ($numLinea + 1) . ": columna {$reg->columna_datos} no existe (hay " . count($cols) . ")";
                continue;
            }

            $valorCrudo = $cols[$columnaIdx];

            if (!is_numeric($valorCrudo)) {
                $errores++;
                $detalle[] = "Línea " . ($numLinea + 1) . ": valor '{$valorCrudo}' no numérico";
                continue;
            }

            $valorCrudo      = (float) $valorCrudo;
            $valorConvertido = round($valorCrudo * $factor, 4);

            [$horaDesde, $bloqueHorario, $fechaLectura] = $this->calcularBloque($horaHasta, $fecha, $esFinDia);

            $horaHastaGuardar = $horaHasta === '24:00' ? '00:00:00' : $horaHasta . ':00';

            $existente = Lectura::where('registrador_id', $reg->id)
                ->where('fecha', $fechaLectura->toDateString())
                ->where('bloque_horario', $bloqueHorario)
                ->where('hora_hasta', $horaHastaGuardar)
                ->first();

            if ($existente) {
                $existente->update([
                    'hora_desde'       => $horaDesde,
                    'valor_crudo'      => $valorCrudo,
                    'valor_convertido' => $valorConvertido,
                ]);
            } else {
                Lectura::create([
                    'registrador_id'   => $reg->id,
                    'fecha'            => $fechaLectura->toDateString(),
                    'bloque_horario'   => $bloqueHorario,
                    'hora_hasta'       => $horaHastaGuardar,
                    'hora_desde'       => $horaDesde,
                    'valor_crudo'      => $valorCrudo,
                    'valor_convertido' => $valorConvertido,
                ]);
            }

            $insertados++;
        }

        if ($errores > 0) {
            Log::warning("ProcesadorPrn [{$contexto}]: {$insertados} insertados, {$errores} errores.");
        }

        return compact('insertados', 'errores', 'detalle');
    }

    /**
     * Para CargaAutomatica: detecta el código del registrador desde el header del archivo.
     * Retorna null si no encuentra la línea "time,...".
     */
    public function detectarCodigoRegistrador(string $contenido): ?string
    {
        foreach ($this->splitLineas($contenido) as $linea) {
            $cols = $this->parsearCols($linea);
            if (isset($cols[0]) && strtolower(trim($cols[0])) === 'time') {
                return isset($cols[1]) ? trim($cols[1]) : null;
            }
        }
        return null;
    }

    // ── Helpers privados ──────────────────────────────────────

    private function splitLineas(string $contenido): array
    {
        return array_filter(
            explode("\n", str_replace("\r\n", "\n", $contenido)),
            fn($l) => trim($l) !== ''
        );
    }

    private function parsearCols(string $linea): array
    {
        return array_map('trim', str_getcsv(trim($linea)));
    }

    /**
     * Limpia la columna 0: elimina el prefijo "comillas" que algunos
     * archivos PRN incluyen en la línea de las 24:00.
     * Ej: "comillas 24:00" → "24:00"
     */
    private function limpiarColCero(string $valor): string
    {
        return trim(preg_replace('/^comillas\s*/i', '', trim($valor)));
    }

    private function esEncabezado(string $valor): bool
    {
        return !preg_match('/^\s*\d/', $valor);
    }

    private function parsearFechaHora(string $valor, ?Carbon $fechaActual): array
    {
        $valor = trim($valor);

        // Fecha completa: "M/D/YY HH:MM" o "M/D/YYYY HH:MM"
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})\s+(\d{1,2}):(\d{2})$/', $valor, $m)) {
            $anio     = (int)$m[3] < 100 ? (int)$m[3] + 2000 : (int)$m[3];
            $fecha    = Carbon::createFromDate($anio, (int)$m[1], (int)$m[2]);
            $esFinDia = ((int)$m[4] === 24 && (int)$m[5] === 0);
            return [$fecha, sprintf('%02d:%02d', (int)$m[4], (int)$m[5]), $esFinDia];
        }

        // Solo hora: "HH:MM" — hereda fecha actual
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $valor, $m) && $fechaActual !== null) {
            $esFinDia = ((int)$m[1] === 24 && (int)$m[2] === 0);
            return [$fechaActual->copy(), sprintf('%02d:%02d', (int)$m[1], (int)$m[2]), $esFinDia];
        }

        return [null, null, false];
    }

    private function calcularBloque(string $horaHasta, Carbon $fecha, bool $esFinDia): array
    {
        if ($esFinDia) {
            return ['23:45:00', 23, $fecha->copy()];
        }

        [$h, $m]   = explode(':', $horaHasta);
        $totalMin  = (int)$h * 60 + (int)$m - 15;
        $horaDesde = sprintf('%02d:%02d:00', intdiv($totalMin, 60), $totalMin % 60);

        return [$horaDesde, intdiv($totalMin, 60), $fecha->copy()];
    }
}