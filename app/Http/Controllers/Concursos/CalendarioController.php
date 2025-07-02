<?php

namespace App\Http\Controllers\Concursos;

use App\Http\Controllers\Controller;
use App\Models\Concursos\Concurso;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    //
    /**
     * Muestra la vista del calendario con los concursos programados.
     */
    public function index(Request $request)
    {
        // Obtener el mes seleccionado o usar el mes actual
        $mes = $request->input('mes', Carbon::now()->format('Y-m'));
        
        // Crear fecha Carbon desde el mes seleccionado
        $fechaSeleccionada = Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        
        // Obtener el primer día del calendario (puede ser del mes anterior)
        $primerDia = $fechaSeleccionada->copy()->startOfMonth()->startOfWeek(Carbon::MONDAY);
        
        // Obtener el último día del calendario (puede ser del mes siguiente)
        $ultimoDia = $fechaSeleccionada->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);
        
        // Crear período para recorrer los días
        $periodo = CarbonPeriod::create($primerDia, $ultimoDia);
        
        // Consultar concursos que cierran en el rango del calendario
        $concursos = Concurso::whereBetween('fecha_cierre', [$primerDia, $ultimoDia])
            ->orderBy('fecha_cierre')
            ->get();
        
        // Organizar concursos por fecha de cierre
        $concursosPorFecha = [];
        foreach ($concursos as $concurso) {
            $fechaCierre = $concurso->fecha_cierre->format('Y-m-d');
            if (!isset($concursosPorFecha[$fechaCierre])) {
                $concursosPorFecha[$fechaCierre] = [];
            }
            $concursosPorFecha[$fechaCierre][] = $concurso;
        }
        
        // Preparar los datos del calendario
        $diasCalendario = [];
        $hoy = Carbon::now()->format('Y-m-d');
        
        foreach ($periodo as $fecha) {
            $fechaStr = $fecha->format('Y-m-d');
            $concursosDia = $concursosPorFecha[$fechaStr] ?? [];
            
            $diasCalendario[] = [
                'fecha' => $fechaStr,
                'numero' => $fecha->day,
                'perteneceMes' => $fecha->month === $fechaSeleccionada->month,
                'esHoy' => $fechaStr === $hoy,
                'cierresConcursos' => count($concursosDia),
                'concursos' => $concursosDia
            ];
        }
        
        // Obtener próximos 30 días de cierres para mostrar debajo del calendario
        $proximosCierres = Concurso::where('fecha_cierre', '>=', Carbon::now())
            ->where('fecha_cierre', '<=', Carbon::now()->addDays(30))
            ->orderBy('fecha_cierre')
            ->get();
        
        // Preparar navegación entre meses
        $mesPrevio = $fechaSeleccionada->copy()->subMonth()->format('Y-m');
        $mesSiguiente = $fechaSeleccionada->copy()->addMonth()->format('Y-m');
        
        return view('concursos.concursos.calendario', compact(
            'diasCalendario',
            'proximosCierres',
            'mes',
            'mesPrevio',
            'mesSiguiente'
        ));
    }
    
    /**
     * Muestra los concursos programados para un día específico.
     */
    public function dia($fecha)
    {
        // Validar fecha
        $fechaCarbon = Carbon::createFromFormat('Y-m-d', $fecha);
        
        // Obtener concursos que cierran en la fecha específica
        $concursos = Concurso::whereDate('fecha_cierre', $fecha)
            ->orderBy('fecha_cierre')
            ->get();
        
        return view('concursos.concursos.calendario-dia', compact('concursos', 'fechaCarbon'));
    }
}
