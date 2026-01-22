<?php

namespace App\Http\Controllers\Concursos;

use App\Models\Concursos\Concurso;
use App\Http\Controllers\Controller;
use App\Mail\Concursos\ConcursoFinalizado;
use App\Mail\Concursos\ProximoCierre;
use App\Models\Concursos\DocumentoTipo;
use App\Models\User;
use App\Models\Usuarios\ManagedJob;
use App\Models\Usuarios\Sede;
use App\Services\ConcursoFileService;
use App\Services\EmailDispatcher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ConcursoController extends Controller
{

    protected $fileService;

    public function __construct(ConcursoFileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Concursos/Concursos/Ver', only: ['index', 'search', 'show', 'terminados']),
            new Middleware('permission:Concursos/Concursos/Crear', only: ['create', 'store', 'edit', 'update']),
            new Middleware('permission:Concursos/Concursos/Editar', only: ['edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //$precargas = Concurso::where('estado_id', 1)->orderBy('fecha_cierre', 'desc')->get();
        $precargas = Concurso::where('estado_id', 1)->where('fecha_cierre', '>', now())->orderBy('fecha_cierre', 'desc')->get();
        $vencidos = Concurso::where('estado_id', 1)->where('fecha_cierre', '<=', now())->orderBy('fecha_cierre', 'desc')->get();
        $activos = Concurso::where('estado_id', 2)->where('fecha_cierre', '>', now())->orderBy('fecha_cierre', 'desc')->get();
        $cerrados = Concurso::where('estado_id', 2)->where('fecha_cierre', '<=', now())->orderBy('fecha_cierre', 'desc')->get();
        $analisis = Concurso::where('estado_id', 3)->orderBy('fecha_cierre', 'desc')->get();
        return view('concursos.concursos.index', 
            compact(
                'precargas', 
                'vencidos', 
                'activos', 
                'cerrados',
                'analisis',
            ));
    }

    public function terminados()
    {
        //
        $terminados = Concurso::where('estado_id', 4)->orWhere('estado_id', 5)->orderBy('numero', 'desc')->get();
        return view('concursos.concursos.terminados', 
            compact(
                'terminados', 
            ));
    }

    public function search()
    {
        //
        return view('concursos.concursos.search');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $users = User::orderBy('legajo')->get();
        $sedes = Sede::all();
        return view('concursos.concursos.create', compact('users', 'sedes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'numero_legajo' => 'string',
            'legajo' => 'string',
            //'user_id' => 'nullable|integer',
            'fecha_inicio' => 'required|date',
            'fecha_cierre' => 'required|date|after:fecha_inicio',
            'sedes' => 'required|array|min:1',
        ], [
            'sedes.required' => 'Debes seleccionar al menos una sede.',
            'sedes.min' => 'Selecciona al menos una sede.',
        ]);

        $concurso = new Concurso();
        $concurso->nombre = $request->input('nombre');
        $concurso->descripcion = $request->input('descripcion');
        $concurso->numero_legajo = $request->input('numero_legajo');
        $concurso->legajo = $request->input('legajo');
        $concurso->fecha_inicio = $request->input('fecha_inicio');
        $concurso->fecha_cierre = $request->input('fecha_cierre');
        $concurso->user_id = Auth::id();
        $concurso->permite_carga = false;

        $lastNum = Concurso::orderBy('numero', 'desc')->first();
        $concurso->numero = $lastNum ? $lastNum->numero + 1 : 1;

        if($request->input('legajo')){
            $explode = explode('?',$request->input('legajo'));
            $concurso->legajo = $explode[0];
        }

        $concurso->save();

        $concurso->historial()->create([
            'estado_id' => 1,
            'user_id' => Auth::id(),
        ]);

        foreach(DocumentoTipo::where('de_concurso', false)->where('obligatorio', true)->get() as $documentoTipo) {
            $concurso->documentos_requeridos()->attach($documentoTipo->id);
        }

        $concurso->sedes()->sync($request->input('sedes'));
        /* 
        $concurso = Concurso::create($validated); */
        return redirect()->route('concursos.concursos.show', $concurso)
            ->with('success', 'Concurso creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Concurso $concurso)
    {
        //
        return view('concursos.concursos.show', compact('concurso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Concurso $concurso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Concurso $concurso)
    {
        //
        $concurso->permite_carga = $request->input('permite_carga');
        $concurso->save();
        return redirect()->route('concursos.concursos.show', $concurso);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Concurso $concurso)
    {
        //
    }

    public function programarEmailsConcurso($concurso)
    {
        /* $proveedores = $concurso->proveedores()->pluck('email')->toArray();
        foreach($concurso->contactos as $contacto) {
            $proveedores[] = $contacto->correo;
        } */

        $correos = $concurso->obtenerProveedoresParticipantes();

        foreach($concurso->contactos as $contacto) {
            $correos[] = $contacto->correo;
        }
        foreach($concurso->invitaciones as $invitacion) {
            foreach($invitacion->proveedor->contactos as $contacto) {
                $correos[] = $contacto->correo;
            }
        }
        $correos = array_unique($correos);
        
        // Job para 48hs antes
        EmailDispatcher::enviarMasivoConTracking(
            $correos,
            new ProximoCierre($concurso),
            'concurso',
            $concurso->id,
            'recordatorio_48hs',
            'concurso_recordatorio',
            "Recordatorio de cierre - Concurso #{$concurso->id}",
            $concurso->fecha_cierre->subHours(48),
            ['concurso', 'recordatorio']
        );
        
        // Job para el cierre
        EmailDispatcher::enviarMasivoConTracking(
            $correos,
            new ConcursoFinalizado($concurso),
            'concurso',
            $concurso->id,
            'notificacion_cierre',
            'concurso_cierre',
            "Notificación de cierre - Concurso #{$concurso->id}",
            $concurso->fecha_cierre,
            ['concurso', 'cierre']
        );
    }

    public function cancelarConcurso($concursoId)
    {
        // Cancelar todos los jobs del concurso
        $cancelados = ManagedJob::cancelByEntity('concurso', $concursoId);
        
        return "Se cancelaron {$cancelados} jobs del concurso";
    }

    public function prorrogarConcurso($concursoId, $nuevaFecha)
    {
        // Cancelar jobs pendientes
        ManagedJob::cancelByEntity('concurso', $concursoId);
        
        // Reprogramar
        $concurso = Concurso::find($concursoId);
        $concurso->fecha_cierre = $nuevaFecha;
        $concurso->save();
        
        $this->programarEmailsConcurso($concurso);
    }
}
