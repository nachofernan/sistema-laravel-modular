<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Lectura;
use App\Models\Despacho\Registrador;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class CargaPrn extends Component
{
    use WithFileUploads;

    public ?int    $registrador_id  = null;
    public         $archivo         = null;

    public bool    $procesando      = false;
    public ?string $resultadoMsg    = null;
    public string  $resultadoTipo   = 'info'; // info | success | error

    public array   $erroresLinea    = [];
    public int     $insertados      = 0;
    public int     $duplicados      = 0;
    public int     $errorCount      = 0;

    // Para mostrar info del registrador seleccionado
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
            'maquinas'          => $reg->maquinas->pluck('codigo')->join(', ')
        ];
    }

    // ── Procesamiento ─────────────────────────────────────────
    public function procesar(): void
    {
        $this->validate([
            'registrador_id' => ['required', 'exists:registradores,id'],
            'archivo'        => ['required', 'file', 'max:10240'], // 10MB máx
        ]);

        $this->procesando   = true;
        $this->resetResultado();

        $reg = Registrador::findOrFail($this->registrador_id);
        $columnaIdx = $reg->columna_datos - 1; // 0-based
        $factor     = (float) $reg->factor_conversion;

        $lineas = file($this->archivo->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $insertados  = 0;
        $duplicados  = 0;
        $errores     = [];
        $fechaActual = null;

        foreach ($lineas as $numLinea => $linea) {
            $nro = $numLinea + 1;

            // Limpiar y parsear CSV
            $cols = str_getcsv(trim($linea));
            $cols = array_map('trim', $cols);
            $cols[0] = trim(str_replace('comillas', '', $cols[0]));

            // Detectar encabezado: primera columna no parseable como fecha/hora
            if ($this->esEncabezado($cols[0])) {
                continue;
            }

            // Parsear fecha/hora
            [$fecha, $horaHasta, $esFinDia] = $this->parsearFechaHora($cols[0], $fechaActual);

            if ($fecha === null) {
                $errores[] = "Línea {$nro}: no se pudo parsear la fecha/hora '{$cols[0]}'";
                continue;
            }

            $fechaActual = $fecha;

            // Validar que exista la columna de datos
            if (!isset($cols[$columnaIdx])) {
                $errores[] = "Línea {$nro}: columna {$reg->columna_datos} no existe (solo hay " . count($cols) . " columnas)";
                continue;
            }

            $valorCrudo = $cols[$columnaIdx];

            if (!is_numeric($valorCrudo)) {
                $errores[] = "Línea {$nro}: valor '{$valorCrudo}' no es numérico";
                continue;
            }

            $valorCrudo     = (float) $valorCrudo;
            $valorConvertido = round($valorCrudo * $factor, 4);

            // Calcular hora_desde y bloque
            [$horaDesde, $bloqueHorario, $fechaLectura] = $this->calcularBloque($horaHasta, $fecha, $esFinDia);

            // Insertar evitando duplicados
            Lectura::updateOrCreate(
                [
                    'registrador_id' => $reg->id,
                    'fecha'          => $fechaLectura,
                    'bloque_horario' => $bloqueHorario,          // ← agregar
                    'hora_hasta'     => $horaHasta === '24:00' ? '00:00:00' : $horaHasta . ':00',
                ],
                [
                    'hora_desde'       => $horaDesde,
                    'valor_crudo'      => $valorCrudo,
                    'valor_convertido' => $valorConvertido,
                ]
            );
            $insertados++;
        }

        $this->insertados  = $insertados;
        $this->duplicados  = $duplicados;
        $this->errorCount  = count($errores);
        $this->erroresLinea = array_slice($errores, 0, 50); // mostramos hasta 50

        if ($insertados > 0) {
            $this->resultadoTipo = $this->errorCount > 0 ? 'warning' : 'success';
            $this->resultadoMsg  = "Proceso finalizado.";
        } else {
            $this->resultadoTipo = 'error';
            $this->resultadoMsg  = "No se insertó ningún registro.";
        }

        $this->procesando = false;
        $this->archivo    = null;
    }

    // ── Helpers de parseo ─────────────────────────────────────

    /**
     * Detecta si una celda es encabezado (no es fecha ni hora).
     */
    protected function esEncabezado(string $valor): bool
    {
        $valor = trim($valor);
        // Si empieza con dígito o espacio+dígito, probablemente es fecha/hora
        if (preg_match('/^\s*\d/', $valor)) {
            return false;
        }
        return true;
    }

    /**
     * Parsea la columna de fecha/hora del archivo.
     * Retorna [fecha Carbon, horaHasta string "HH:MM", esFinDia bool]
     */
    protected function parsearFechaHora(string $valor, ?Carbon $fechaActual): array
    {
        $valor = trim($valor);

        // Caso: fecha completa "M/D/YY HH:MM" o "M/D/YY 24:00"
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})\s+(\d{1,2}):(\d{2})$/', $valor, $m)) {
            $mes  = (int)$m[1];
            $dia  = (int)$m[2];
            $anio = (int)$m[3] < 100 ? (int)$m[3] + 2000 : (int)$m[3];
            $hora = (int)$m[4];
            $min  = (int)$m[5];

            $esFinDia = ($hora === 24 && $min === 0);

            // Si es 24:00, la fecha del registro es ese día (no el siguiente)
            $fecha = Carbon::createFromDate($anio, $mes, $dia);

            $horaStr = sprintf('%02d:%02d', $hora, $min);
            return [$fecha, $horaStr, $esFinDia];
        }

        // Caso: solo hora "HH:MM" hereda fecha actual
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $valor, $m) && $fechaActual !== null) {
            $hora = (int)$m[1];
            $min  = (int)$m[2];
            $esFinDia = ($hora === 24 && $min === 0);
            $horaStr  = sprintf('%02d:%02d', $hora, $min);
            return [$fechaActual->copy(), $horaStr, $esFinDia];
        }

        return [null, null, false];
    }

    /**
     * A partir de hora_hasta, calcula hora_desde, bloque horario y fecha real de la lectura.
     *
     * Regla: el registro de las HH:MM corresponde al bloque HH-1 (o bloque 23 si HH=00 o 24:00).
     * Ejemplos:
     *   00:15 → desde 00:00, hasta 00:15, bloque 0
     *   01:00 → desde 00:45, hasta 01:00, bloque 0
     *   23:45 → desde 23:30, hasta 23:45, bloque 23
     *   24:00 → desde 23:45, hasta 00:00, bloque 23 (fecha = día del archivo)
     */
    protected function calcularBloque(string $horaHasta, Carbon $fecha, bool $esFinDia): array
    {
        if ($esFinDia) {
            // 24:00 es el último cuarto del día actual
            return ['23:45:00', 23, $fecha->copy()];
        }

        [$h, $m] = explode(':', $horaHasta);
        $h = (int)$h;
        $m = (int)$m;

        // Restar 15 minutos para obtener hora_desde
        $totalMin  = $h * 60 + $m - 15;
        $hDesde    = intdiv($totalMin, 60);
        $mDesde    = $totalMin % 60;
        $horaDesde = sprintf('%02d:%02d:00', $hDesde, $mDesde);

        // El bloque es la hora del intervalo (0 a 23)
        // Un registro de las HH:MM pertenece al bloque de la hora previa si MM > 0
        // o a la hora actual - 1 si MM = 0
        // Simplificado: bloque = hora de hora_desde
        $bloqueHorario = $hDesde;

        return [$horaDesde, $bloqueHorario, $fecha->copy()];
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