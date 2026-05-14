<?php

namespace App\Livewire\Despacho;

use App\Models\Despacho\Lectura;
use App\Models\Despacho\Maquina;
use Livewire\Component;

class VisorDiario extends Component
{
    public ?int    $maquina_id = null;
    public ?string $fecha      = null;
    public array   $expandidos = [];

    // Calculadora
    public bool    $modalCalculadora    = false;
    public ?int    $calc_registrador_id = null;
    public ?string $calc_fecha_desde    = null;
    public string  $calc_hora_desde     = '00:00';
    public ?string $calc_fecha_hasta    = null;
    public string  $calc_hora_hasta     = '23:45';
    public string  $calc_precio         = '1.0000';
    public ?array  $calc_resultado      = null;

    public function getMaquinasProperty()
    {
        return Maquina::where('activa', true)->orderBy('codigo')->get();
    }

    public function getMaquinasConRegistradoresProperty()
    {
        return Maquina::where('activa', true)
            ->with(['registradores' => fn($q) => $q->where('activo', true)->orderByRaw("CASE tipo
                WHEN 'principal' THEN 1
                WHEN 'respaldo'  THEN 2
                WHEN 'control'   THEN 3
                WHEN 'auxiliar'  THEN 4
                ELSE 5 END")->orderBy('codigo')])
            ->orderBy('codigo')
            ->get()
            ->filter(fn($m) => $m->registradores->isNotEmpty());
    }

    public function abrirCalculadora(): void
    {
        $this->calc_resultado = null;
        $this->modalCalculadora = true;
    }

    public function cerrarCalculadora(): void
    {
        $this->modalCalculadora = false;
    }

    public function calcular(): void
    {
        $this->validate([
            'calc_registrador_id' => 'required|integer',
            'calc_fecha_desde'    => 'required|date',
            'calc_hora_desde'     => 'required',
            'calc_fecha_hasta'    => 'required|date',
            'calc_hora_hasta'     => 'required',
            'calc_precio'         => 'required|numeric|min:0',
        ]);

        $desde = $this->calc_fecha_desde . ' ' . $this->calc_hora_desde . ':00';
        $hasta = $this->calc_fecha_hasta . ' ' . $this->calc_hora_hasta . ':00';

        $lecturas = Lectura::where('registrador_id', $this->calc_registrador_id)
            ->whereRaw('TIMESTAMP(fecha, hora_desde) >= ?', [$desde])
            ->whereRaw('TIMESTAMP(fecha, hora_desde) <= ?', [$hasta])
            ->get();

        $total  = $lecturas->sum('valor_convertido');
        $precio = (float) $this->calc_precio;

        $this->calc_resultado = [
            'total'         => $total,
            'total_precio'  => $total * $precio,
            'cantidad'      => $lecturas->count(),
        ];
    }

    public function updatedMaquinaId(): void
    {
        $this->expandidos = [];
    }

    public function updatedFecha(): void
    {
        $this->expandidos = [];
    }

    public function toggleBloque(int $bloque): void
    {
        if (in_array($bloque, $this->expandidos)) {
            $this->expandidos = array_values(
                array_filter($this->expandidos, fn($b) => $b !== $bloque)
            );
        } else {
            $this->expandidos[] = $bloque;
        }
    }

    public function render()
    {
        $bloques      = [];
        $registradores = collect();

        if ($this->maquina_id && $this->fecha) {

            $maquina = Maquina::find($this->maquina_id);

            // Todos los registradores activos de la máquina, ordenados por tipo
            $registradores = $maquina->registradores()
                ->where('activo', true)
                ->orderByRaw("CASE tipo
                        WHEN 'principal' THEN 1
                        WHEN 'respaldo'  THEN 2
                        WHEN 'control'   THEN 3
                        WHEN 'auxiliar'  THEN 4
                        ELSE 5
                    END")
                ->orderBy('codigo')
                ->get();

            if ($registradores->isNotEmpty()) {

                // Una sola query con todas las lecturas del día para todos los registradores
                $todasLecturas = Lectura::whereIn('registrador_id', $registradores->pluck('id'))
                    ->whereDate('fecha', $this->fecha)
                    ->orderBy('bloque_horario')
                    ->orderBy('hora_hasta')
                    ->get()
                    ->groupBy('registrador_id');

                for ($h = 0; $h <= 23; $h++) {

                    // Datos por registrador para este bloque
                    $columnas = [];
                    $estadoGlobal = 'completo';

                    foreach ($registradores as $reg) {
                        $cuartos        = ($todasLecturas[$reg->id] ?? collect())
                                            ->where('bloque_horario', $h)
                                            ->values();
                        $totalCuartos   = $cuartos->count();
                        $sumaConvertida = $cuartos->sum('valor_convertido');

                        if ($totalCuartos === 0) {
                            $estado = 'vacio';
                        } elseif ($totalCuartos < 4) {
                            $estado = 'incompleto';
                        } else {
                            $estado = 'completo';
                        }

                        // El estado global del bloque es el peor de todos los registradores
                        if ($estado === 'vacio' && $estadoGlobal !== 'vacio') {
                            $estadoGlobal = 'vacio';
                        } elseif ($estado === 'incompleto' && $estadoGlobal === 'completo') {
                            $estadoGlobal = 'incompleto';
                        }

                        $columnas[$reg->id] = [
                            'cuartos'        => $cuartos,
                            'total_cuartos'  => $totalCuartos,
                            'suma_convertida'=> $sumaConvertida,
                            'estado'         => $estado,
                        ];
                    }

                    $bloques[$h] = [
                        'bloque'   => $h,
                        'label'    => sprintf('%02d:00 — %02d:00', $h, $h === 23 ? 0 : $h + 1),
                        'columnas' => $columnas,
                        'estado'   => $estadoGlobal,
                        'expandido'=> in_array($h, $this->expandidos),
                    ];
                }
            }
        }

        return view('livewire.despacho.visor-diario', [
            'maquinas'                  => $this->maquinas,
            'registradores'             => $registradores,
            'bloques'                   => $bloques,
            'maquinasConRegistradores'  => $this->maquinasConRegistradores,
        ]);
    }
}