<?php

namespace App\Livewire\Adminip\Ips\Index;

use App\Models\User;
use App\Rules\UniqueValue;
use Livewire\Component;

class Editar extends Component
{
    public $open = false;
    public $users;

    public $ip_db;

    public $nombre;
    public $ip;
    public $mac;
    public $descripcion;
    public $user;
    public $password;
    public $user_id;

    public function mount($ip)
    {
        $this->users = User::all();
        $this->ip_db = $ip;

        $this->nombre = $ip->nombre;
        $this->ip = $ip->ip;
        $this->mac = $ip->mac;
        $this->descripcion = $ip->descripcion;
        $this->user = $ip->user;
        $this->password = $ip->password;
        $this->user_id = $ip->user_id;
    }

    public function guardar()
    {
        $validated = $this->validate([ 
            'nombre' => 'required',
            'ip' => [
                'required', 
                'regex:/^(?=\d+\.\d+\.\d+\.\d+$)(?:(?:25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]|[0-9])\.?){4}$/', 
                new UniqueValue('ips', 'ip', 'adminip', $this->ip_db->id)
            ],
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

        $this->ip_db->update($validated);

        return redirect()->route('adminip.ips.index');
    }

    public function render()
    {
        return view('livewire.adminip.ips.index.editar');
    }
}
