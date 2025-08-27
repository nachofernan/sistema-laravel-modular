<?php

namespace App\Http\Controllers\Automotores;

use App\Http\Controllers\Controller;
use App\Models\Automotores\Vehiculo;
use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Routing\Controllers\Middleware;

class VehiculoController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('permission:Automotores/Vehículos/Ver', only: ['index', 'show']),
            new Middleware('permission:Automotores/Vehículos/Editar', only: ['edit', 'update']),
            new Middleware('permission:Automotores/Vehículos/Crear', only: ['store', 'create']),
            new Middleware('permission:Automotores/Vehículos/Eliminar', only: ['destroy']),
        ];
    }
    /**
     * Mostrar la lista de vehículos
     */
    public function index(): View
    {
        $vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->paginate(15);
        return view('automotores.vehiculos.index', compact('vehiculos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo vehículo
     */
    public function create(): View
    {
        return view('automotores.vehiculos.create');
    }

    /**
     * Almacenar un nuevo vehículo
     */
    public function store(Request $request)
    {
        $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'patente' => 'required|string|max:20|unique:automotores.vehiculos,patente',
            'kilometraje' => 'nullable|integer|min:0',
        ]);

        $vehiculo = Vehiculo::create($request->only(['marca', 'modelo', 'patente', 'kilometraje']));

        return redirect()->route('automotores.vehiculos.show', $vehiculo)->with('info', 'Vehículo registrado exitosamente');
    }

    /**
     * Mostrar un vehículo específico
     */
    public function show(Vehiculo $vehiculo): View
    {
        $vehiculo->load('copres');
        return view('automotores.vehiculos.show', compact('vehiculo'));
    }

    /**
     * Mostrar el formulario para editar un vehículo
     */
    public function edit(Vehiculo $vehiculo): View
    {
        return view('automotores.vehiculos.edit', compact('vehiculo'));
    }

    /**
     * Actualizar un vehículo específico
     */
    public function update(Request $request, Vehiculo $vehiculo)
    {
        $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'patente' => 'required|string|max:20|unique:automotores.vehiculos,patente,' . $vehiculo->id,
            'kilometraje' => 'nullable|integer|min:0',
        ]);

        $vehiculo->update($request->only(['marca', 'modelo', 'patente', 'kilometraje']));

        return redirect()->route('automotores.vehiculos.show', $vehiculo)->with('info', 'Vehículo actualizado exitosamente');
    }

    /**
     * Eliminar un vehículo específico
     */
    public function destroy(Vehiculo $vehiculo)
    {
        // Verificar si tiene COPRES asociadas
        if ($vehiculo->copres()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el vehículo porque tiene COPRES asociadas');
        }

        $vehiculo->delete();

        return redirect()->route('automotores.vehiculos.index')->with('info', 'Vehículo eliminado exitosamente');
    }


}
