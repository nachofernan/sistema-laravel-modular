<?php

namespace App\Livewire\Automotores\Services;

use App\Models\Automotores\Service;
use App\Models\Automotores\Vehiculo;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceManager extends Component
{
    use WithPagination;

    public Vehiculo $vehiculo;
    public $showModal = false;
    public $editingService = null;
    public $isEditing = false;

    // Campos del formulario
    public $kilometros = '';
    public $fecha_service = '';

    protected $rules = [
        'kilometros' => 'required|integer|min:0',
        'fecha_service' => 'required|date',
    ];

    protected $messages = [
        'kilometros.required' => 'Los kilómetros son obligatorios.',
        'kilometros.integer' => 'Los kilómetros deben ser un número entero.',
        'kilometros.min' => 'Los kilómetros no pueden ser negativos.',
        'fecha_service.required' => 'La fecha del service es obligatoria.',
        'fecha_service.date' => 'La fecha del service debe ser una fecha válida.',
    ];

    public function mount(Vehiculo $vehiculo)
    {
        $this->vehiculo = $vehiculo;
    }

    protected $listeners = ['open-service-modal' => 'openModal'];

    public function openModal($serviceId = null)
    {
        if ($serviceId) {
            $this->editingService = Service::findOrFail($serviceId);
            $this->isEditing = true;
            $this->kilometros = $this->editingService->kilometros;
            $this->fecha_service = $this->editingService->fecha_service->format('Y-m-d');
        } else {
            $this->resetForm();
            $this->isEditing = false;
            $this->editingService = null;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->isEditing = false;
        $this->editingService = null;
    }

    public function resetForm()
    {
        $this->kilometros = '';
        $this->fecha_service = '';
    }

    public function save()
    {
        $this->validate();

        $data = [
            'vehiculo_id' => $this->vehiculo->id,
            'kilometros' => $this->kilometros,
            'fecha_service' => $this->fecha_service,
        ];

        if ($this->isEditing && $this->editingService) {
            $this->editingService->update($data);
            session()->flash('message', 'Service actualizado correctamente.');
        } else {
            Service::create($data);
            session()->flash('message', 'Service creado correctamente.');
        }

        $this->closeModal();
        $this->dispatch('service-updated');
    }

    public function delete($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->delete();
        
        session()->flash('message', 'Service eliminado correctamente.');
        $this->dispatch('service-updated');
    }

    public function render()
    {
        $services = $this->vehiculo->services()
            ->orderBy('fecha_service', 'desc')
            ->paginate(10);

        return view('livewire.automotores.services.service-manager', [
            'services' => $services
        ]);
    }
}
