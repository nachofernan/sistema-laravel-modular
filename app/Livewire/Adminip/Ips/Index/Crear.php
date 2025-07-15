<?php

namespace App\Livewire\Adminip\Ips\Index;

use App\Models\Adminip\IP;
use App\Models\User;
use Livewire\Component;

class Crear extends Component
{
    public $showModal = false;
    public $users;

    public $nombre;
    public $ip;
    public $mac;
    public $descripcion;
    public $user;
    public $password;
    public $user_id;

    protected $listeners = ['openCreateModal' => 'openModal'];

    public function mount()
    {
        $this->users = User::orderBy('realname')->get();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nombre = '';
        $this->ip = '';
        $this->mac = '';
        $this->descripcion = '';
        $this->user = '';
        $this->password = '';
        $this->user_id = '';
        $this->resetErrorBag();
    }

    public function guardar()
    {
        $validated = $this->validate([ 
            'nombre' => 'required',
            'ip' => ['required', 'regex:/^(?=\d+\.\d+\.\d+\.\d+$)(?:(?:25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]|[0-9])\.?){4}$/', 'unique:App\Models\Adminip\IP,ip'],
            'mac' => 'nullable',
            'descripcion' => 'nullable',
            'user' => 'nullable',
            'password' => 'nullable',
            'user_id' => 'nullable',
        ], [
            'required' => 'El campo :Attribute es necesario',
            'regex' => 'El campo no es una IP válida',
            'unique' => 'La IP ya está registrada en la base de datos',
        ]);

        $ips = explode('.', $validated['ip']);
        $validated['bloque_a'] = $ips[0];
        $validated['bloque_b'] = $ips[1];
        $validated['bloque_c'] = $ips[2];
        $validated['bloque_d'] = $ips[3];

        IP::create($validated);

        session()->flash('message', 'IP creada correctamente.');
        $this->closeModal();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.adminip.ips.index.crear');
    }
}
