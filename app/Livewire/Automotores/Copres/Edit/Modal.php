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
    public $numero_ticket_factura;
    public $cuit;
    public $vehiculo_id;
    public $litros;
    public $precio_x_litro;
    public $importe_final;
    public $km_vehiculo;
    public $kz;
    public $salida;
    public $reentrada;
    public $user_id_chofer;
    
    // Datos para los selects
    public $vehiculos;
    public $usuarios;

    protected $rules = [
        'fecha' => 'required|date',
        'numero_ticket_factura' => 'required|string|max:255',
        'cuit' => 'required|string|max:20',
        'vehiculo_id' => 'required|exists:automotores.vehiculos,id',
        'litros' => 'nullable|numeric|min:0',
        'precio_x_litro' => 'nullable|numeric|min:0',
        'importe_final' => 'required|numeric|min:0',
        'km_vehiculo' => 'nullable|integer|min:0',
        'kz' => 'nullable|integer',
        'salida' => 'nullable|date',
        'reentrada' => 'nullable|date',
        'user_id_chofer' => 'required',
    ];

    protected $listeners = ['openEditModal' => 'openModal'];

    public function mount()
    {
        $this->vehiculos = Vehiculo::orderBy('marca')->orderBy('modelo')->get();
        $this->usuarios = User::orderBy('name')->get();
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
        $this->reset(['copresId', 'copres', 'fecha', 'numero_ticket_factura', 'cuit', 'vehiculo_id', 'litros', 'precio_x_litro', 'importe_final', 'km_vehiculo', 'kz', 'salida', 'reentrada', 'user_id_chofer']);
    }

    public function loadData()
    {
        $this->fecha = $this->copres->fecha;
        $this->numero_ticket_factura = $this->copres->numero_ticket_factura;
        $this->cuit = $this->copres->cuit;
        $this->vehiculo_id = $this->copres->vehiculo_id;
        $this->litros = $this->copres->litros;
        $this->precio_x_litro = $this->copres->precio_x_litro;
        $this->importe_final = $this->copres->importe_final;
        $this->km_vehiculo = $this->copres->km_vehiculo;
        $this->kz = $this->copres->kz;
        $this->salida = $this->copres->salida;
        $this->reentrada = $this->copres->reentrada;
        $this->user_id_chofer = $this->copres->user_id_chofer;
    }

    public function save()
    {
        $this->validate();

        $this->copres->update([
            'fecha' => $this->fecha,
            'numero_ticket_factura' => $this->numero_ticket_factura,
            'cuit' => $this->cuit,
            'vehiculo_id' => $this->vehiculo_id,
            'litros' => $this->litros,
            'precio_x_litro' => $this->precio_x_litro,
            'importe_final' => $this->importe_final,
            'km_vehiculo' => $this->km_vehiculo,
            'kz' => $this->kz,
            'salida' => $this->salida,
            'reentrada' => $this->reentrada,
            'user_id_chofer' => $this->user_id_chofer,
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
