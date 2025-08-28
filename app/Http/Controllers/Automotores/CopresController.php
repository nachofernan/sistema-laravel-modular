<?php

namespace App\Http\Controllers\Automotores;

use App\Http\Controllers\Controller;
use App\Models\Automotores\Copres;
use App\Models\Automotores\Vehiculo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;
use Illuminate\Routing\Controllers\Middleware;

class CopresController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Automotores/COPRES/Ver', only: ['index', 'show']),
            new Middleware('permission:Automotores/COPRES/Crear', only: ['store', 'create']),
            new Middleware('permission:Automotores/COPRES/Eliminar', only: ['destroy']),
        ];
    }
    /**
     * Mostrar la lista de COPRES
     */
    public function index(): View
    {
        $copres = Copres::with(['vehiculo', 'creator'])
            ->orderBy('fecha', 'desc')
            ->paginate(15);
        
        return view('automotores.copres.index', compact('copres'));
    }

    /**
     * Mostrar el formulario para crear una nueva COPRES
     */
    public function create(): View
    {
        $vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->get();
        
        return view('automotores.copres.create', compact('vehiculos'));
    }

    /**
     * Almacenar una nueva COPRES
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'numero_ticket_factura' => 'nullable|string|max:255',
            'cuit' => 'nullable|string|max:20',
            'vehiculo_id' => 'required|exists:automotores.vehiculos,id',
            'litros' => 'nullable|numeric|min:0',
            'precio_x_litro' => 'nullable|numeric|min:0',
            'importe_final' => 'required|numeric|min:0',
            'km_vehiculo' => 'nullable|integer|min:0',
            'kz' => 'nullable|integer',
            'salida' => 'nullable|date',
            'reentrada' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['user_id_creator'] = Auth::id();
        
        $copres = Copres::create($data);

        // Actualizar kilometraje del vehículo si se proporcionó
        if ($request->filled('km_vehiculo')) {
            $vehiculo = Vehiculo::find($request->vehiculo_id);
            $vehiculo->update(['kilometraje' => $request->km_vehiculo]);
        }

        return redirect()->route('automotores.copres.index')->with('info', 'COPRES registrada exitosamente');
    }

    /**
     * Mostrar una COPRES específica
     */
    public function show(Copres $copres): View
    {
        $copres->load(['vehiculo', 'creator']);
        return view('automotores.copres.show', compact('copres'));
    }



    /**
     * Eliminar una COPRES específica
     */
    public function destroy(Copres $copres)
    {
        $copres->delete();

        return redirect()->route('automotores.copres.index')->with('info', 'COPRES eliminada exitosamente');
    }


}
