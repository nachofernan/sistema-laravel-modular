<?php

namespace App\Livewire\Automotores\Copres\Edit;

use App\Models\Automotores\Copres;
use App\Models\Automotores\Vehiculo;
use App\Models\User;
use Livewire\Component;

class Modal extends Component
{
    public $showModal = false;
    public $copresId;
    public $copres;
    
    // Campos editables
    public $fecha;
    public $vehiculo_id;
    public $km_vehiculo;
    public $numero_ticket_factura;
    public $cuit;
    public $es_original;
    public $litros;
    public $precio_x_litro;
    public $importe_final;
    public $salida;
    public $reentrada;
    public $kz;
    // Datos para los selects
    public $vehiculos;

    protected $rules = [
        'fecha' => 'required|date',
        'vehiculo_id' => 'required|exists:automotores.vehiculos,id',
        'km_vehiculo' => 'nullable|integer|min:0',
        'numero_ticket_factura' => 'nullable|string|max:255',
        'cuit' => 'nullable|string|max:20',
        'es_original' => 'boolean',
        'litros' => 'nullable|numeric|min:0',
        'precio_x_litro' => 'nullable|numeric|min:0',
        'importe_final' => 'required|numeric|min:0',
        'salida' => 'nullable|date',
        'reentrada' => 'nullable|date',
        'kz' => 'nullable|integer',
    ];

    protected $listeners = ['openEditModal' => 'openModal'];

    public function mount()
    {
        $this->vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->get();
    }

    public function openModal($copresId)
    {
        $this->copresId = $copresId;
        $this->copres = Copres::findOrFail($copresId);
        $this->loadData();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['copresId', 'copres', 'fecha', 'vehiculo_id', 'km_vehiculo', 'numero_ticket_factura', 'cuit', 'es_original', 'litros', 'precio_x_litro', 'importe_final', 'salida', 'reentrada', 'kz']);
    }

    public function loadData()
    {
        $this->fecha = $this->copres->fecha ? $this->copres->fecha->format('Y-m-d') : null;
        $this->vehiculo_id = $this->copres->vehiculo_id;
        $this->km_vehiculo = $this->copres->km_vehiculo;
        $this->numero_ticket_factura = $this->copres->numero_ticket_factura;
        $this->cuit = $this->copres->cuit;
        $this->es_original = $this->copres->es_original;
        $this->litros = $this->copres->litros;
        $this->precio_x_litro = $this->copres->precio_x_litro;
        $this->importe_final = $this->copres->importe_final;
        $this->salida = $this->copres->salida ? $this->copres->salida->format('Y-m-d') : null;
        $this->reentrada = $this->copres->reentrada ? $this->copres->reentrada->format('Y-m-d') : null;
        $this->kz = $this->copres->kz;
    }

    public function save()
    {
        $this->validate();

        $this->copres->update([
            'fecha' => $this->fecha,
            'vehiculo_id' => $this->vehiculo_id,
            'km_vehiculo' => $this->km_vehiculo,
            'numero_ticket_factura' => $this->numero_ticket_factura,
            'cuit' => $this->cuit,
            'es_original' => $this->es_original,
            'litros' => $this->litros,
            'precio_x_litro' => $this->precio_x_litro,
            'importe_final' => $this->importe_final,
            'salida' => empty($this->salida) ? null : $this->salida,
            'reentrada' => empty($this->reentrada) ? null : $this->reentrada,
            'kz' => empty($this->kz) ? null : $this->kz,
        ]);

        // Actualizar kilometraje del vehículo si se proporcionó
        if ($this->km_vehiculo) {
            $vehiculo = Vehiculo::find($this->vehiculo_id);
            $vehiculo->update(['kilometraje' => $this->km_vehiculo]);
        }

        $this->closeModal();
        session()->flash('info', 'COPRES actualizada exitosamente');
        return redirect()->route('automotores.copres.index');
    }

    public function render()
    {
        return view('livewire.automotores.copres.edit.modal');
    }
}
